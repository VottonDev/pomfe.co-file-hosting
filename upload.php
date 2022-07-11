<?php

session_start();
require_once 'vendor/autoload.php';

/**
 * Handles POST uploads, generates filenames, moves files around and commits
 * uploaded metadata to database.
 */

require_once 'classes/Response.class.php';
require_once 'classes/UploadException.class.php';
require_once 'classes/UploadedFile.class.php';
require_once 'includes/database.inc.php';

/**
 * Generates a random name for the file, retrying until we get an unused one.
 *
 * @param UploadedFile $file
 *
 * @return string
 * @throws Exception
 */
function generateName($file)
{
    global $db;
    global $doubledots;

    // We start at N retries, and --N until we give up
    $tries = POMF_FILES_RETRIES;
    $length = POMF_FILES_LENGTH;
    $ext = pathinfo($file->name, PATHINFO_EXTENSION);

    // Check if extension is a double-dot extension and, if true, override $ext
    $revname = strrev($file->name);
    foreach ($doubledots as $ddot) {
        if (stripos($revname, $ddot) === 0) {
            $ext = strrev($ddot);
        }
    }

    do {
        // Iterate until we reach the maximum number of retries
        if ($tries-- === 0) {
            throw new Exception(
                'Gave up trying to find an unused name',
                500
            ); // HTTP status code "500 Internal Server Error"
        }

        $chars = ID_CHARSET;
        $name = '';
        for ($i = 0; $i < $length; ++$i) {
            $name .= $chars[random_int(0, strlen($chars))];
        }

        // Add the extension to the file name
        if ($ext !== '') {
            $name .= '.'.$ext;
        }

        // Check if a file with the same name does already exist in the database
        $q = $db->prepare('SELECT COUNT(filename) FROM files WHERE filename = (:name)');
        $q->bindValue(':name', $name, PDO::PARAM_STR);
        $q->execute();
        $result = $q->fetchColumn();
        // If it does, generate a new name
    } while ($result > 0);

    return $name;
}

/**
 * Handles the uploading and db entry for a file.
 *
 * @param UploadedFile $file
 *
 * @return array
 * @throws UploadException
 * @throws Exception
 */
function uploadFile($file)
{
    global $db;
    global $FILTER_MODE;
    global $FILTER_MIME;

    // Handle file errors
    if ($file->error) {
        throw new UploadException($file->error);
    }

    if (isset($_SESSION['Max_Upload'])) {
        $POMF_MAX_UPLOAD_SIZE = $_SESSION['Max_Upload'];
    }
    $max_size = $POMF_MAX_UPLOAD_SIZE * 1048576;

    if ($file->size > $max_size) {
        throw new UploadException("File exceeds upload limit");
    }
    // Check if mime type is blocked and check if filter mode is enabled
    if ($FILTER_MODE && in_array($file->type, $FILTER_MIME)) {
        throw new UploadException("File type is blocked");
    }

    // If IP_LOGGING is enabled in settings then we log the IP of the uploader
    if (IP_LOGGING) {
        $q = $db->prepare('INSERT INTO files (ip) VALUES (:ip)');
        $q->bindValue(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $q->execute();
    }

    // Check if a file with the same hash and size (a file which is the same)
    // does already exist in the database; if it does, return the proper link
    // and data. PHP deletes the temporary file just uploaded automatically.
    $q = $db->prepare('SELECT filename, COUNT(*) AS count FROM files WHERE hash = (:hash) '.
        'AND size = (:size)');
    $q->bindValue(':hash', $file->getSha256(), PDO::PARAM_STR);
    $q->bindValue(':size', $file->size, PDO::PARAM_INT);
    $q->execute();
    $result = $q->fetch();
    if ($result['count'] > 0) {
        return array(
            'hash' => $file->getSha256(),
            'name' => $file->name,
            'url' => POMF_URL.rawurlencode($result['filename']),
            'size' => $file->size,
        );
    }

    // Generate a name for the file
    $newname = generateName($file);

    // Just storing the temp file thing to the var tmp to make it easier
    $tmp =  $file->tempfile;
    // Now I'm opening the temporary file (the thing above)
    $oFile = fopen($tmp, "r");
    // Now I'm reading the first 4 bytes, converting them to hexadecimal and storing them to a variable
    $mNum = bin2hex(fread($oFile, 4));
    // This is just for testing - it's disabled for now to prevent database spam
    /*	$dispIn = $db->prepare('INSERT INTO test (info) VALUES (:msg)');
        $dispIn->bindParam(":msg", $mNum);
        $dispIn->execute(); */

    // Block executable files
    switch ($mNum) {
        case "4d5a9000":
            throw new UploadException(UPLOAD_ERR_EXTENSION);
            break;
        case "4d5a9001":
            throw new UploadException(UPLOAD_ERR_EXTENSION);
            break;
        default:
            break;
    }

    // Use ClamAV to scan the file
    if (POMF_CLAMAV_SCAN) {
        $clam = new Network();
        $result = $clam->fileScan($tmp);
        if ($result !== true) {
            throw new UploadException(UPLOAD_ERR_MALICIOUS);
        }
        // Update ClamAV's database
        $clam->reload();
        // Shutdown ClamAV
        $clam->shutdown();
    }

    // Store the file's full file path in memory
    $uploadFile = POMF_FILES_ROOT . $newname;

    // Attempt to move it to the static directory
    if (!move_uploaded_file($file->tempfile, $uploadFile)) {
        throw new Exception(
            'Failed to move file to destination',
            500
        ); // HTTP status code "500 Internal Server Error"
    }

    // Need to change permissions for the new file to make it world readable
    if (!chmod($uploadFile, 0644)) {
        throw new Exception(
            'Failed to change file permissions',
            500
        ); // HTTP status code "500 Internal Server Error"
    }

    // Add it to the database
    if (isset($_SESSION['email']) && $_COOKIE['IsAnon'] == "False") {
        // Query if user is logged in (insert user id together with other data)
        $q = $db->prepare('INSERT INTO files (hash, originalname, filename, size, date, ' .
            'expire, delid, user) VALUES (:hash, :orig, :name, :size, :date, ' .
            ':exp, :del, :user)');
        $q->bindValue(':user', $_SESSION['email'], PDO::PARAM_STR);
    } else {
        // Query if user is NOT logged in
        $q = $db->prepare('INSERT INTO files (hash, originalname, filename, size, date, ' .
            'expire, delid) VALUES (:hash, :orig, :name, :size, :date, :exp, :del)');
    }

    // Common parameters binding
    $q->bindValue(':hash', $file->getSha256(), PDO::PARAM_STR);
    $q->bindValue(':orig', strip_tags($file->name), PDO::PARAM_STR);
    $q->bindValue(':name', $newname, PDO::PARAM_STR);
    $q->bindValue(':size', $file->size, PDO::PARAM_INT);
    $q->bindValue(':date', date('Y-m-d'), PDO::PARAM_STR);
    $q->bindValue(':exp', null, PDO::PARAM_STR);
    $q->bindValue(':del', sha256($file->tempfile), PDO::PARAM_STR);
    $q->execute();

    return array(
        'hash' => $file->getSha256(),
        'name' => $file->name,
        'url' => POMF_URL.rawurlencode($newname),
        'size' => $file->size,
    );
}

/**
 * Reorder files array by file.
 *
 * @param  $_FILES
 *
 * @return array
 */
function diverseArray($files)
{
    $result = array();

    foreach ($files as $key1 => $value1) {
        foreach ($value1 as $key2 => $value2) {
            $result[$key2][$key1] = $value2;
        }
    }

    return $result;
}

/**
 * Reorganize the $_FILES array into something saner.
 *
 * @param  $_FILES
 *
 * @return array
 */
function refiles($files)
{
    $result = array();
    $files = diverseArray($files);

    foreach ($files as $file) {
        $f = new UploadedFile();
        $f->name = $file['name'];
        $f->mime = $file['type'];
        $f->size = $file['size'];
        $f->tempfile = $file['tmp_name'];
        $f->error = $file['error'];
        //$f->expire   = $file['expire'];
        $result[] = $f;
    }

    return $result;
}

$type = isset($_GET['output']) ? $_GET['output'] : 'json';
$response = new Response($type);

if (isset($_FILES['files'])) {
    $uploads = refiles($_FILES['files']);

    try {
        foreach ($uploads as $upload) {
            $res[] = uploadFile($upload);
        }
        $response->send($res);
    } catch (Exception $e) {
        $response->error($e->getCode(), $e->getMessage());
    }
} else {
    $response->error(400, 'No input file(s)');
}
