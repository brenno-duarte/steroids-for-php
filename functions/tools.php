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

/**
 * Return period in hours, minutes or seconds using microtime function
 *
 * @param float $endtime
 * @param float $starttime
 * 
 * @return mixed
 */
function microtime_period(float $endtime, float $starttime): mixed
{
    $duration = $endtime - $starttime;
    $hours = (int) ($duration / 60 / 60);
    $minutes = (int) ($duration / 60) - $hours * 60;
    $seconds = (int) $duration - $hours * 60 * 60 - $minutes * 60;
    return ($hours == 0 ? "00" : $hours) . ":" . ($minutes == 0 ? "00" : ($minutes < 10 ? "0" . $minutes : $minutes)) . ":" . ($seconds == 0 ? "00" : ($seconds < 10 ? "0" . $seconds : $seconds));
}
