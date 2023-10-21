<?php

require_once(__DIR__ . '/image_function.php');

class Binhusenstore_image
{
    var $image_dir = "uploaded/binhusenstore/";
    
    function __construct()
    {
        
    }
    
    public function upload_image()
    {
        // request
        $req = Flight::request();
        $image = $req->files['image'];

        // Validate the image file.
        if ($image['error'] !== UPLOAD_ERR_OK) {
            // Handle the error here.

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to upload image, check the data you sent'
                ), 400
            );
            return;
        }

        // Get the image file extension.
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);

        // Check the image file extension.
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            // Handle the error here.

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Invalid image data' . $extension
                ), 400
            );
            return;
        }

        // Generate a unique filename for the image.
        $filename = uniqid() . '.' . $extension;
        $path_default_image = $this->image_dir . $filename;
        $is_uploaded = move_uploaded_file($image['tmp_name'], $this->image_dir . $filename);

        $filename_small_image = uniqid() . '-small.' . $extension;
        $path_small_image = $this->image_dir . $filename_small_image;
        resize_image_and_save($path_default_image, 320, 320, false, $extension, $path_small_image);

        if($is_uploaded) {

            // Return a success response.
            Flight::json(
                array(
                    'success' => true,
                    'filename' => $filename_small_image
                ), 201
            );
        } else {
            
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Failed to move image to server folder!',
                ), 500
            );
        }
            
    }

    public function remove_image($filename) {

        $filepath = $this->image_dir . $filename;

        $is_filename_exist = file_exists($filepath);

        if($is_filename_exist) {

            unlink($filepath);

            $error = error_get_last();

            if ($error) {

                Flight::json([
                        'success' => false,
                        'message' => 'An error occurred while deleting the file: ' . $error['message']
                    ], 500
                );
            } else {

                Flight::json([
                        'success' => true,
                        'message' => 'The file was successfully deleted.'
                    ], 200
                );
            }

        }

        else {

            Flight::json([
                    'success' => false,
                    'message' => 'Image not found'
                ], 404
            );
        }
    }
}
