<?php declare(strict_types=1);

if (!function_exists('file_contains')) {
    /**
     * Check if the file contains the specified string
     *
     * @param string $filename
     * @param string $str
     * @return bool
     */
    function file_contains(string $filename, string $str): bool
    {
        $handle = fopen($filename, 'r');

        if ($handle === false) return false;

        $valid = false;
        $len = max(2 * strlen($str), 256);
        $prev = '';

        while (!feof($handle)) {
            $cur = fread($handle, $len);

            if (strpos($prev . $cur, $str) !== false) {
                $valid = true;
                break;
            }

            $prev = $cur;
        }

        fclose($handle);
        return $valid;
    }
}

if (!function_exists('fnmatch_extended')) {
    /**
     * Match path against an extended wildcard pattern.
     *
     * @param string $pattern
     * @param string $path
     * @return bool
     */
    function fnmatch_extended(string $pattern, string $path): bool
    {
        $quoted = preg_quote($pattern, '~');

        $step1 = strtr($quoted, [
            '\?' => '[^/]', '\*' => '[^/]*', '/\*\*' => '(?:/.*)?', '#' => '\d+', '\[' => '[',
            '\]' => ']', '\-' => '-', '\{' => '{', '\}' => '}'
        ]);

        $step2 = preg_replace_callback('~{[^}]+}~', function ($part) {
            return '(?:' . substr(strtr($part[0], ',', '|'), 1, -1) . ')';
        }, $step1);

        $regex = rawurldecode($step2);
        return (bool) preg_match("~^{$regex}\$~", $path);
    }
}

if (!function_exists('file_extension')) {
    /**
     * Get extension from file
     *
     * @param string $file_name
     *
     * @return string
     */
    function file_extension(string $file_name): string
    {
        if (!file_exists_without_cache($file_name))
            exit("File '" . $file_name . "' not found or isn't a file");

        $info = pathinfo($file_name);
        $info = $info['extension'];

        return $info;
    }
}

if (!function_exists('file_exists_without_cache')) {
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

        // clear cached results
        clearstatcache(true, $file_path);

        // trim path
        $file_dir = trim(dirname($file_path));

        // normalize path separator
        $file_dir = str_replace('/', DIRECTORY_SEPARATOR, $file_dir) . DIRECTORY_SEPARATOR;

        // trim file name
        $file_name = trim(basename($file_path));

        // rebuild path
        $file_path = $file_dir . "{$file_name}";

        // If you simply want to check that some file (not directory) exists,
        // and concerned about performance, try is_file() instead.
        // It seems like is_file() is almost 2x faster when a file exists
        // and about the same when it doesn't.

        // $file_exists = is_file($file_path);
        $file_exists = file_exists($file_path);

        return $file_exists;
    }
}

if (!function_exists('is_dir_empty')) {
    /**
     * Check if directory is empty
     *
     * @param string $dir
     * 
     * @return bool
     */
    function is_dir_empty(string $dir): bool
    {
        if (!is_dir($dir)) mkdir($dir);

        $count = 0;

        foreach (new \DirectoryIterator($dir) as $fileInfo) {
            if (
                $fileInfo->isDot() ||
                $fileInfo->getBasename() == '.DS_Store'
            ) {
                continue;
            }

            $count++;
        }

        clearstatcache(true, $dir);
        return ($count === 0);
    }
}
