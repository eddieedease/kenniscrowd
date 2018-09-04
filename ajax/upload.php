<?php

// settings
$max_file_size = 2000 * 2000; // 200kb
$valid_exts = array('jpeg', 'jpg', 'png', 'gif');
// thumbnail sizes
$sizes = array(250 => 250);

if ($_FILES['file']['size'] < $max_file_size) {
    //echo ("here?");
    // get file extension
    $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $valid_exts)) {
        /* resize image */
        foreach ($sizes as $w => $h) {
            $files[] = resize($w, $h);
            //echo json_encode($_FILES["file"]);
        }
    } else {
        $msg = 'Geen geldig bestand';
    }
} else {
    $msg = 'De afbeelding is te groot';
}

function resize($width, $height) {
    /* Get original image x y */
    list($w, $h) = getimagesize($_FILES['file']['tmp_name']);
    /* calculate new image size with ratio */
    $ratio = max($width / $w, $height / $h);
    $h = ceil($height / $ratio);
    $x = ($w - $width / $ratio) / 2;
    $w = ceil($width / $ratio);
    /* new file name */
    $path = '../uploads/' . date("y") . date("j") . date("H") . date("s") . $width . 'x' . $height . '_'. $_FILES['file']['name'];;
    /* read binary data from image file */
    
    echo json_encode($path);
    
    
    $imgString = file_get_contents($_FILES['file']['tmp_name']);
    /* create image from string */
    $image = imagecreatefromstring($imgString);
    $tmp = imagecreatetruecolor($width, $height);
    imagecopyresampled($tmp, $image, 0, 0, $x, 0, $width, $height, $w, $h);
    /* Save image */
    switch ($_FILES['file']['type']) {
        case 'image/jpeg':
            imagejpeg($tmp, $path, 100);
            break;
        case 'image/png':
            imagepng($tmp, $path, 0);
            break;
        case 'image/gif':
            imagegif($tmp, $path);
            break;
        default:
            exit;
            break;
    }
    return $path;
    
    /* cleanup memory */
    imagedestroy($image);
    imagedestroy($tmp);
}
?>
