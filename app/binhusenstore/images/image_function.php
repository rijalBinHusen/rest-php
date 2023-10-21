<?php

function resize_image_and_save($file, $w, $h, $crop=FALSE, $extension, $savePath) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    switch($extension) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($dst, $savePath, 100);
            break;

        case 'gif':
            imagegif($dst, $savePath);
            break;

        case 'png':
            // *** Scale quality from 0-100 to 0-9
            $scaleQuality = round((100/100) * 9);

            // *** Invert quality setting as 0 is best, not 9
            $invertScaleQuality = 9 - $scaleQuality;

            imagepng($dst, $savePath, $invertScaleQuality);
            break;

        // ... etc

        default:
            // *** No extension - No save.
            break;
    }
}
