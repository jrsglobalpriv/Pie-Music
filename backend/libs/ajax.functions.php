<?php
include_once('classconfig.php');
include_once('lib.ytartist.php');
$lbyt = new LibYT(constant("LASTFMAPI"));

$ytTitle = $_GET['ytTitle'];
$ytArtist = $_GET['ytArtist'];


if(isset($ytTitle) && isset($ytArtist)){
	$lbyt->findVideo($ytArtist, $ytTitle);
}

?>