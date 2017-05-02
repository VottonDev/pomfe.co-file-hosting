<?php
$images = array(
	'img/1.png'
);
if (headers_sent() === false) {
	header('Location: ' . $images[array_rand($images)], true, 303);
}
?>
