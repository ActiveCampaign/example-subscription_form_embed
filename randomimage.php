<?php

header('P3P: CP="NON BUS INT NAV COM ADM CON CUR IVA IVD OTP PSA PSD TEL SAM"');
@session_start();
$alphanum = 'ABCDEFGHJKMNPQRSTVWXYZ23456789';
$rand = substr(str_shuffle($alphanum), 0, 5);
if ( !isset($_SESSION['image_random_value']) ) {
	$_SESSION['image_random_value'] = array();
}
$_SESSION['image_random_value'][md5($rand)] = time();
$bgNum = rand(1, 4);
$image = imagecreatefromjpeg(dirname(__FILE__) . "/random_background$bgNum.jpg");
$textColor = imagecolorallocate($image, 0, 0, 0);
imagestring($image, 5, 5, 8, $rand, $textColor);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-type: image/jpeg');
imagejpeg($image);
imagedestroy($image);

?>