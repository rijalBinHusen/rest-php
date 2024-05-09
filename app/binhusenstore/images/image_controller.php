<?php

require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/image_function.php');

class Binhusenstore_image
{
    var $image_dir = "uploaded/binhusenstore/";
    protected $database;

    function __construct()
    {
        $this->database = Query_builder::getInstance();
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
                ),
                400
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
                ),
                400
            );
            return;
        }

        // Generate a unique filename for the image.
        $uniquee_name = uniqid();
        $filename = $uniquee_name . '.' . $extension;
        $path_default_image = $this->image_dir . $filename;
        $is_uploaded = move_uploaded_file($image['tmp_name'], $this->image_dir . $filename);

        $filename_small_image = $uniquee_name . '-small.' . $extension;
        $path_small_image = $this->image_dir . $filename_small_image;
        resize_image_and_save($path_default_image, 320, 320, false, $extension, $path_small_image);

        if ($is_uploaded) {

            // Return a success response.
            Flight::json(
                array(
                    'success' => true,
                    'filename' => $filename_small_image
                ),
                201
            );
        } else {

            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Failed to move image to server folder!',
                ),
                500
            );
        }
    }

    public function remove_image($filename)
    {

        $is_small_image = strpos($filename, "-small") > -1;

        $another_file_name = "";

        if ($is_small_image) {

            $another_file_name = str_replace("-small", "", $filename);
        } else {

            $another_file_name = str_replace(".", "-small.", $filename);
        }

        $filepath = $this->image_dir . $filename;
        $is_filename_exist = file_exists($filepath);

        $file_name_to_search_on_db = "";

        if ($is_small_image) $file_name_to_search_on_db = str_replace(".jpeg", "", $another_file_name);
        else $file_name_to_search_on_db = str_replace(".jpeg", "", $filename);

        $find_image_on_db = $this->database->select_where_like("binhusenstore_products", "images", $file_name_to_search_on_db);
        $is_image_exists_on_db = is_array($find_image_on_db);

        if ($is_image_exists_on_db) {
            Flight::json(
                [
                    'success' => true,
                    'message' => 'The file used by some record on database.'
                ],
                200
            );
            return;
        };

        $another_file_path = $this->image_dir . $another_file_name;
        $is_another_image_exists = file_exists($another_file_path);
        if ($is_another_image_exists) unlink($another_file_path);


        if ($is_filename_exist) {

            unlink($filepath);

            $error = error_get_last();

            if ($error) {

                Flight::json(
                    [
                        'success' => false,
                        'message' => 'An error occurred while deleting the file: ' . $error['message']
                    ],
                    500
                );
            } else {

                Flight::json(
                    [
                        'success' => true,
                        'message' => 'The file deleted.'
                    ],
                    200
                );
            }
        } else {

            Flight::json(
                [
                    'success' => false,
                    'message' => 'Image not found'
                ],
                404
            );
        }
    }
    public function remove_image_operation($filename)
    {

        $is_small_image = strpos($filename, "-small") > -1;

        $another_file_name = "";

        if ($is_small_image) {

            $another_file_name = str_replace("-small", "", $filename);
        } else {

            $another_file_name = str_replace(".", "-small.", $filename);
        }

        $filepath = $this->image_dir . $filename;
        $is_filename_exist = file_exists($filepath);

        $file_name_to_search_on_db = "";

        if ($is_small_image) {
            $explode_name = explode(".", $another_file_name);
            $file_name_to_search_on_db = $explode_name[0];
        } else {
            $explode_name = explode(".", $filename);
            $file_name_to_search_on_db = $explode_name[0];
        }

        $find_image_on_db = $this->database->select_where_like("binhusenstore_products", "images", $file_name_to_search_on_db);
        $is_image_exists_on_db = is_array($find_image_on_db);

        if ($is_image_exists_on_db) return true;

        $another_file_path = $this->image_dir . $another_file_name;
        $is_another_image_exists = file_exists($another_file_path);
        if ($is_another_image_exists) unlink($another_file_path);


        if ($is_filename_exist) {

            unlink($filepath);

            $error = error_get_last();
            if ($error) return false;
            else return true;
        }

        return true;
    }
}
