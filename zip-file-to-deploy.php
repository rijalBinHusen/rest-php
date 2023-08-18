<?php

// This PHP script will zip all files inside a folder.

// Set the name of the folder to be zipped.
// $folder_name = "utils";

// Set the name of the zipped file.
// $zipped_file = "my_folder.zip";

// Create the zipped file.
// $zip = new ZipArchive();
// $zip->open($zipped_file, ZipArchive::CREATE);

// Recursively add all files in the folder to the zipped file.
// $files = scandir($folder_name);

// foreach ($files as $file) {
//     echo $file;
//     // if ($file != "." && $file != "..") {
//         // $zip->addFile($folder_name . "/" . $file);
//     // }
// }

// $zip->close();

// Display a message indicating that the files have been zipped.
// echo "The files in the folder $folder_name have been zipped.";

// ==============================================================


// This PHP script will zip a folder with a directory tree.

// Set the name of the folder to be zipped.
// $folder_name = "utils";

// // Set the name of the zipped file.
// $zipped_file = "my_folder.zip";

// // Create the zipped file.
// $zip = new ZipArchive();
// $zip->open($zipped_file, ZipArchive::CREATE);

// // Recursively add all files and directories in the folder to the zipped file.
// $files = scandir($folder_name);
// foreach ($files as $file) {
//     if ($file != "." && $file != "..") {
//         if (is_dir($folder_name . "/" . $file)) {
//             $zip->addEmptyDir($file);
//         } else {
//             $zip->addFile($folder_name . "/" . $file);
//         }
//     }
// }



// ================================================

// This PHP script will show a folder tree.

// ============== This function is working very well

function getDirContents($dir, &$results = array()) {
    $black_list_to_not_zip = array(".", "..", ".git");

    $files = scandir($dir);

    foreach ($files as $file) {
      $is_on_black_list = in_array($file, $black_list_to_not_zip);

        $location = $dir . "/" . $file;
        
        if (!is_dir($location)) {
          
          $results[] = $location;
        } else if ($is_on_black_list === false) {

          $results[] = $location;
        }
    }

    return $results;

}

function write_array_to_file($file_name, $array) {

    // Open the txt file for writing.
    $file = fopen($file_name, "w");

    // Write the array to the file.
    fwrite($file, implode("\n", $array));

    // Close the file.
    fclose($file);

}

function read_file_to_array($file_name) {
    // Open the txt file for reading.
    $file = fopen($file_name, "r");

    // Read the contents of the file into an array.
    $array = explode("\n", fread($file, filesize($file_name)));

    // Close the file.
    fclose($file);

    return $array;

}

function clear_concole() {
    
    echo chr(27).chr(91).'H'.chr(27).chr(91).'J';
}

function select_directory_to_exclude() {
    // clear the terminal
    clear_concole();
    echo "Please select file or folder that you won't put it into zip file:\n\n";

    $exclude_directories_file_name = "excluded_file_name.txt";
    $exclude_directories = read_file_to_array($exclude_directories_file_name);
    // scan all file and folder
    $directories = getDirContents(".");
    // show in prompt ask user, 
    // prompt, is there any file to not include to zip?.

    $include_directories = array();
    foreach($directories as $key => $value) {
        $is_directory_excluded = in_array($value, $exclude_directories);

        if(!$is_directory_excluded) {

            array_push($include_directories, $value);
            echo "[". $key ."] " .$value ."\n";
        }
    }
    
    echo "\nInput F for finish\n\n";
    
    $input = trim(fgets(STDIN));

    if(is_numeric($input)) {

        array_push($exclude_directories, $directories[$input]);
        // save it into somewhere
        write_array_to_file($exclude_directories_file_name, $exclude_directories);
    }

    if($input !== "F") {

        select_directory_to_exclude();        
    }
    
    clear_concole();
    return $include_directories;       
}

function start_to_zip_all () {
    
    // ask user, 
    // prompt, is there any file to not include to zip?, and save it into somewhere
    $directory_to_zip = select_directory_to_exclude();
    echo "Daftar directory yang akan di zip\n";
    var_dump($directory_to_zip);
    
    // prompt, is it the first time to deploy to server
    // if yes include the vendor directory
    
    // else
    // dont include the vendor directory

}
start_to_zip_all();