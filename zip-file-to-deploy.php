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


// ================================================

// This PHP script will show a folder tree.

// ============== This function is working very well

// function getDirContents($dir, &$results = array()) {
//     $files = scandir($dir);

//     foreach ($files as $key => $value) {

//         $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        
//         if (!is_dir($path)) {

//             $results[] = $path;

//         } else if ($value != "." && $value != ".." && $value != ".git") {

//             getDirContents($path, $results);

//             $results[] = $path;

//         }
//     }

//     return $results;

// }


// var_dump(getDirContents("."));

// ==============================================================


// This PHP script will zip a folder with a directory tree.

// Set the name of the folder to be zipped.
$folder_name = "utils";

// Set the name of the zipped file.
$zipped_file = "my_folder.zip";

// Create the zipped file.
$zip = new ZipArchive();
$zip->open($zipped_file, ZipArchive::CREATE);

// Recursively add all files and directories in the folder to the zipped file.
$files = scandir($folder_name);
foreach ($files as $file) {
    if ($file != "." && $file != "..") {
        if (is_dir($folder_name . "/" . $file)) {
            $zip->addEmptyDir($file);
        } else {
            $zip->addFile($folder_name . "/" . $file);
        }
    }
}

$zip->close();

// Display a message indicating that the files have been zipped.
echo "The files in the folder $folder_name have been zipped.";

?>
