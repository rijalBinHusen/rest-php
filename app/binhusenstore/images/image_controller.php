<?php

class Binhusenstore_image
{
    function __construct()
    {
        
    }
    
    public function upload_image()
    {
        // request
        $req = Flight::request();
        $image = $req->files;

        // Validate the image file.
        // if ($image['error'] !== UPLOAD_ERR_OK) {
        //     // Handle the error here.

        //     Flight::json(
        //         array(
        //             'success' => false,
        //             'message' => 'Failed to upload image, check the data you sent'
        //         ), 400
        //     );
        //     return;
        // }

        // Get the image file extension.
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);

        // Check the image file extension.
        // if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
        //     // Handle the error here.

        //     Flight::json(
        //         array(
        //             'success' => false,
        //             'message' => 'Invalid image data' . $extension
        //         ), 400
        //     );
        //     return;
        // }

        // Generate a unique filename for the image.
        $filename = uniqid() . '.' . $extension;

        // Save the image file to the server.
        move_uploaded_file($image['tmp_name'], 'binhusenstore/images/' . $filename);

        // Return a success response.
        Flight::json(
            array(
                'success' => true,
                'filename' => $filename
            ), 201
        );
    }

    public function remove_image($filename) {

        $filepath = 'binhusenstore/images/' . $filename;

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
                    ]
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
