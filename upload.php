<?php

function crop_image($fileTmpName, $max_resolution){
    $source = imagecreatefromstring(file_get_contents($fileTmpName));
    $original_width = imagesx($source);
    $original_height = imagesy($source);

    if($original_height>$original_width) {
        $ratio = $max_resolution / $original_width;
        $new_width = $max_resolution;
        $new_height = $original_height * $ratio;

        $diff = $new_height - $new_width;
        $x = 0;
        $y = round($diff/2);
    }else{
        $ratio = $max_resolution /$original_height;
        $new_height = $max_resolution;
        $new_width = $original_width * $ratio;

        $diff = $new_width - $new_height;
        $x = round($diff/2);
        $y = 0;
    }

    if($source){
        $new_image =  imagecreatetruecolor(round($new_width), round($new_height));
        imagecopyresampled($new_image, $source, 0,0,0,0, round($new_width), round($new_height), $original_width, $original_height);

        $cropped_image =  imagecreatetruecolor($max_resolution, $max_resolution);
        imagecopyresampled($cropped_image, $new_image, 0,0,$x,$y, $max_resolution, $max_resolution, $max_resolution, $max_resolution);

        imagejpeg($cropped_image,$fileTmpName);
    }
}

if(isset($_POST['submit'])) {
    $file = $_FILES['avatar'];

    $fileName = $_FILES['avatar']['name'];
    $fileTmpName = $_FILES['avatar']['tmp_name'];
    $fileSize = $_FILES['avatar']['size'];
    $fileError = $_FILES['avatar']['error'];
    $fileType = $_FILES['avatar']['type'];

    // get the extension of the file
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    // set an array with allowed file types
    $allowed = array('jpg', 'jpeg', 'png');

    if (in_array($fileActualExt, $allowed)){
        if ($fileError === 0){
            if ($fileSize < 1000000){

                // getting M.academy overlay
                $frame = imagecreatefrompng("trained-by-macademy-overlay.png");
                imagealphablending($frame, true);
                $width2 = imagesx($frame);
                $height2 = imagesy($frame);

                //size of the uploaded image
                list($width, $height) = getimagesize($fileTmpName);
                $newwidth = 800;
                $newheight = 800;

                if($width !== $newwidth || $height !== $newheight){
                    crop_image($fileTmpName,800);
                }
                $photo = imagecreatefromstring(file_get_contents($fileTmpName));

                // add overlay to the photo
                imagecopyresized($photo, $frame, 0, 0,0, 0, $width2, $height2, $width2, $height2);
                imagejpeg($photo,"macademyAvatar.jpeg",100);

                echo "<img src='macademyAvatar.jpeg'>";

            } else {
                echo "File is too big";
            }
        } else {
            echo "Error while uploading file";
        }
    } else {
        echo "Please use another type of file.";
    }




}
