<?php

require_once(__DIR__ . "/utils/piece/validator.php");

function getDirContents($dir, $is_nested, $directories_black_list = array(), &$results = array()) {
    $default_directories_exclude = array(".", "..", ".git");

    $directories_exclude = array_merge($default_directories_exclude, $directories_black_list);

    $files = scandir($dir);

    foreach ($files as $file) {
        $location = $dir . "/" . $file;

        $is_location_in_blacklist = in_array($location, $directories_black_list);
        $is_directory_in_blacklist = in_array($file, $directories_exclude);
        
        $is_on_black_list = $is_location_in_blacklist || $is_directory_in_blacklist;
        
        if (!is_dir($location) && $is_on_black_list === false) {
            
            $results[] = $location;
        } else if ($is_on_black_list === false) {

            $results[] = $location;
            if($is_nested) {

                getDirContents($location, true, $directories_black_list, $results);
            }

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
    $is_file_exists = file_exists($file_name);

    if(!$is_file_exists) {
        fopen($file_name, "w");

        return array();
    }
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

function select_directory_to_exclude(&$include_directories = array()) {
    $include_directories = array();
    // clear the terminal
    clear_concole();

    $exclude_directories_file_name = "excluded_file_name.txt";
    $exclude_directories = read_file_to_array($exclude_directories_file_name);
    // scan all file and folder
    $directories = getDirContents(".", false, array());
    // show in prompt ask user, 
    // prompt, is there any file to not include to zip?.

    foreach($directories as $key => $value) {
        $is_directory_excluded = in_array($value, $exclude_directories);

        if(!$is_directory_excluded) {

            array_push($include_directories, $value);
            echo "[". $key ."] " .$value ."\n";
        }
    }
    
    echo "\nSelect file or folder that you won't put it into zip file, Press enter for finish\n\n";
    $input = trim(fgets(STDIN));

    if(is_numeric($input)) {

        array_push($exclude_directories, $directories[$input]);
        // save it into somewhere
        write_array_to_file($exclude_directories_file_name, $exclude_directories);
    }

    if($input !== "") {

        select_directory_to_exclude($include_directories);        
    }

    clear_concole();
    echo "Please wait...\n";
    return $include_directories;       
}

function zip_all_file_and_folder ($locations, $zip_file_name, $last_modified_time_minimum_to_wrap) {
  
    $zip_archive = new ZipArchive;
  
    $zip_archive_open = $zip_archive->open($zip_file_name, (ZipArchive::CREATE | ZipArchive::OVERWRITE));
  
    if ($zip_archive_open !== true) {
      die("Faild to create archive!\n");
    }
  
    foreach ($locations as $location) {
        
        $last_modified_time = filemtime($location);
        $is_less_than_time = $last_modified_time < $last_modified_time_minimum_to_wrap;

        if($is_less_than_time) continue;
  
        if (is_dir($location)) {
    
            $zip_archive->addEmptyDir($location);
        } else {
    
            $real_path = realpath($location);
            $zip_archive->addFile($real_path, $location);
        }
    
        $is_failed_to_zip = $zip_archive->status != ZipArchive::ER_OK;
    
        if ($is_failed_to_zip) {
            echo "Failed to write " . $location . " files to zip\n";
        }
    }
  
    $zip_archive->close();
  }
  

function start_to_zip_all () {
  
    $validator = new Validator();
    // this should be contain date now and time
    $zip_file_name = "Ready_to_deploy.zip";
    $last_modified_time_to_wrap = 0;
    // inform to user
    // find file zip before now
    $is_zipped_before = file_exists($zip_file_name);
    if($is_zipped_before) {
        // get last modified
        $last_modified_time = filemtime($zip_file_name);
        echo "File and folder with last modified more than ". date("Y-m-d H:i:s", $last_modified_time) ." will be wrapped" . PHP_EOL;
        echo "If you want some specific last modified please input (Year-Month-Date) below and press enter to continue" . PHP_EOL;
        $input_date = trim(fgets(STDIN));

        if($input_date != "") {
   
            if($validator->isYMDDate($input_date)) {
                
                $last_modified_time_to_wrap = strtotime($input_date);
            } else {
                
                echo "Invalid date, zip file cancelled...";
                return;
            }
        } else {

            $last_modified_time_to_wrap = $last_modified_time;
            unlink($zip_file_name);
        }


    } else {

        echo "Input some specific last modified file to wrap, left it blank if you want wrap all file, Enter to continue" . PHP_EOL;
        $input_date = trim(fgets(STDIN));


        if($input_date != "") {

            $unix_time = strtotime($input_date);
   
            if($unix_time) {
                
                $last_modified_time_to_wrap = strtotime($unix_time);
            } else {
                
                echo "Invalid date, zip file cancelled...";
                return;
            }
        } else {

            $last_modified_time_to_wrap = 0;
        }
    }
    
    // ask user, 
    // prompt, is there any file to not include to zip?, and save it into somewhere
    $directory_to_zip = select_directory_to_exclude();
    sleep(1);
    echo "Directories that would wrap into zip file" . PHP_EOL . PHP_EOL;

    foreach($directory_to_zip as $key => $value) {
        echo "[". $key ."] " .$value . PHP_EOL;
    }
    
    echo "Press enter to continue, Input any key to cancel..." . PHP_EOL . PHP_EOL;
    $input = trim(fgets(STDIN));

    if($input != "") {
        
        echo "Cancelled...\n";
    }

    else {

        echo "zipping file....\n";

        $exclude_directories_file_name = "excluded_file_name.txt";
        $exclude_directories = read_file_to_array($exclude_directories_file_name);

        $file_and_folder_to_zip = getDirContents(".", true, $exclude_directories);
        zip_all_file_and_folder($file_and_folder_to_zip, $zip_file_name, $last_modified_time_to_wrap);
        
        echo "zip finished...\n";
    }

}

start_to_zip_all();