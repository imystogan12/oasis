<?php

QRcode::png($text, $file, $ecc, $pixel_size, $frame_size);

include 'phpqrcode/qrlib.php';
$text = " PRODUCT ID 23456";

QRcode::png($text);


?>