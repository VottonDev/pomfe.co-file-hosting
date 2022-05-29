<?php

class UploadedFile
{
    /* Public attributes */
    public $name;
    public $mime;
    public $size;
    public $tempfile;
    public $error;

    /**
     * SHA-256 checksum
     *
     */
    private $sha256;

    /**
     * Generates the SHA-256 or returns the cached SHA-1 hash for the file.
     *
     * @return string|false $sha256 The SHA-256 hash of the file or false if the file does not exist.
     */
    public function getSha256()
    {
        if (!$this->sha256) {
            if (!file_exists($this->tempfile)) {
                return false;
            }

            $this->sha256 = hash_file('sha256', $this->tempfile);
        }

        return $this->sha256;
    }
}
    
