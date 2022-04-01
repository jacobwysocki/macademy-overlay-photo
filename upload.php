<?php

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
                    // Load
                    $thumb = imagecreatetruecolor($newwidth, $newheight);
                    $source = imagecreatefromstring(file_get_contents($fileTmpName));
                    // resize uploaded image to 800x800
                    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                    // Output
                    imagejpeg($thumb,'resized.jpeg');

                    $photo = imagecreatefromjpeg('resized.jpeg');
                } else {
                    $photo = imagecreatefromstring(file_get_contents($fileTmpName));
                }

                // add overlay to the photo
                imagecopyresized($photo, $frame, 0, 0,0, 0, $width2, $height2, $width2, $height2);
                imagejpeg($photo,"macademyAvatar.jpg",100);

                echo "<img src='macademyAvatar.jpg'>";

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
