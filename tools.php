<?php

/**
 * Get extension from file
 *
 * @param string $file_name
 * 
 * @return string
 */
function file_extension(string $file_name): string
{
    clearstatcache(true, $file_name);

    if (!file_exists($file_name)) {
        trigger_error("File '" . $file_name . "' not found or isn't a file", E_USER_ERROR);
        exit;
    }

    $info = pathinfo($file_name);
    $info = $info['extension'];

    return $info;
}

/**
 * Checks whether a file or directory exists without store result in cache
 *
 * @param string $file_path
 * 
 * @return bool
 */
function file_exists_without_cache(string $file_path): bool
{
    $file_exists = false;

    //clear cached results
    clearstatcache(true, $file_path);

    //trim path
    $file_dir = trim(dirname($file_path));

    //normalize path separator
    $file_dir = str_replace('/', DIRECTORY_SEPARATOR, $file_dir) . DIRECTORY_SEPARATOR;

    //trim file name
    $file_name = trim(basename($file_path));

    //rebuild path
    $file_path = $file_dir . "{$file_name}";

    //If you simply want to check that some file (not directory) exists, 
    //and concerned about performance, try is_file() instead.
    //It seems like is_file() is almost 2x faster when a file exists 
    //and about the same when it doesn't.

    //$file_exists = is_file($file_path);
    $file_exists = file_exists($file_path);

    return $file_exists;
}

/**
 * Recursively loads all php files in all subdirectories of the given path
 *
 * @param string $directory
 *
 * @throws \Exception
 */
function autoload_files(string $directory)
{
    // Ensure this path exists
    if (!is_dir($directory)) {
        return;
    }

    // Get a listing of the current directory
    $scanned_dir = scandir($directory);

    // Ignore these items from scandir
    $ignore = [
        '.',
        '..'
    ];

    // Remove the ignored items
    $scanned_dir = array_diff($scanned_dir, $ignore);

    if (empty($scanned_dir)) {
        return;
    }

    if (count($scanned_dir) > 250) {
        throw new RuntimeException('Too many files attempted to load via autoload');
    }

    foreach ($scanned_dir as $item) {

        $filename  = $directory . '/' . $item;
        $real_path = realpath($filename);

        if (false === $real_path) {
            continue;
        }

        $filetype = filetype($real_path);

        if (empty($filetype)) {
            continue;
        }

        // If it's a directory then recursively load it
        if ('dir' === $filetype) {
            autoload_files($real_path);
        } // If it's a file, let's try to load it
        else if ('file' === $filetype) {

            if (true !== is_readable($real_path)) {
                continue;
            }

            // Don't allow files that have been uploaded
            if (is_uploaded_file($real_path)) {
                continue;
            }

            // Only for files that really exist
            if (true !== file_exists($real_path)) {
                continue;
            }

            $pathinfo = pathinfo($real_path);

            // An empty filename wouldn't be a good idea
            if (empty($pathinfo['filename'])) {
                continue;
            }

            // Sorry, need an extension
            if (empty($pathinfo['extension'])) {
                continue;
            }

            // Actually, we want just a PHP extension!
            if ('php' !== $pathinfo['extension']) {
                continue;
            }

            $filesize = filesize($real_path);

            // Don't include negative sized files
            if ($filesize < 0) {
                throw new RuntimeException('File size is negative, not autoloading');
            }

            // Don't include files that are greater than 300kb
            if ($filesize > 300000) {
                throw new RuntimeException('File size is greater than 300kb, not autoloading');
            }

            require_once($real_path);
        }
    }
}

/**
 * Load an object with data in json format
 *
 * @param mixed $object
 * @param string $json
 * 
 * @return mixed
 */
function load_object_json(mixed $object, string $json): mixed
{
    $dcod = json_decode($json);
    $prop = get_object_vars($dcod);
    
    foreach ($prop as $key => $lock) {
        if (property_exists($object,  $key)) {
            if (is_object($dcod->$key)) {
                load_object_json($object->$key, json_encode($dcod->$key));
            } else {
                $object->$key = $dcod->$key;
            }
        }
    }

    return $object;
}
