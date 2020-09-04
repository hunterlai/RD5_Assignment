<?php
Header("Content-type:image/PNG");
srand((double)microtime()*1000000);

session_start();
$authnum=$_SESSION['authnum'];


$im=imagecreate(65,25);

$black=ImageColorAllocate($im,0,0,0);
$green=ImageColorAllocate($im,0,255,0);
$white=ImageColorAllocate($im,255,255,255);
$gray=ImageColorAllocate($im,200,200,200);

imagefill($im,0,0,$gray);


imagestring($im,5,10,3,$authnum,$black);

for($i=0;$i<200;$i++){
    $randcolor=ImageColorAllocate($im,rand(0,255),rand(0,255),rand(0,255));
    imagesetpixel($im,rand()%70,rand()%30,$randcolor);
}
ImagePNG($im);
ImageDestroy($im);
?>