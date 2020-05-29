<?php

namespace Foris\Easy\Support;

use Foris\Easy\Support\Exceptions\FileNotFountException;

/**
 * Class Filesystem base on Illuminate\Filesystem\Filesystem
 */
class Filesystem
{
    /**
     * Determine if a file or directory exists.
     *
     * @param $path
     * @return bool
     */
    public static function exists($path)
    {
        return file_exists($path);
    }

    /**
     * Determine if the given path is a directory.
     *
     * @param $path
     * @return bool
     */
    public static function isFile($path)
    {
        return is_file($path);
    }

    /**
     * Determine if the given path is a directory.
     *
     * @param  string  $directory
     * @return bool
     */
    public static function isDirectory($directory)
    {
        return is_dir($directory);
    }

    /**
     * Determine if the given path is readable.
     *
     * @param  string  $path
     * @return bool
     */
    public static function isReadable($path)
    {
        return is_readable($path);
    }

    /**
     * Determine if the given path is writable.
     *
     * @param  string  $path
     * @return bool
     */
    public static function isWritable($path)
    {
        return is_writable($path);
    }

    /**
     * Get file content
     *
     * @param      $path
     * @return bool|string
     * @throws FileNotFountException
     */
    public static function get($path)
    {
        if (!static::exists($path)) {
            throw new FileNotFountException("File does not exist at path {$path}");
        }

        return file_get_contents($path);
    }

    /**
     * Write the contents of a file.
     *
     * @param      $path
     * @param      $contents
     * @param bool $lock
     * @return bool|int
     */
    public static function put($path, $contents, $lock = false)
    {
        return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
    }

    /**
     * Prepend to a file.
     *
     * @param  string $path
     * @param  string $data
     * @return int
     * @throws FileNotFountException
     */
    public static function prepend($path, $data)
    {
        if (static::exists($path)) {
            return static::put($path, $data . static::get($path));
        }

        return static::put($path, $data);
    }

    /**
     * Append to a file.
     *
     * @param  string  $path
     * @param  string  $data
     * @return int
     */
    public static function append($path, $data)
    {
        return file_put_contents($path, $data, FILE_APPEND);
    }

    /**
     * Create a directory.
     *
     * @param  string  $path
     * @param  int     $mode
     * @param  bool    $recursive
     * @param  bool    $force
     * @return bool
     */
    public static function makeDirectory($path, $mode = 0755, $recursive = false, $force = false)
    {
        if ($force) {
            return @mkdir($path, $mode, $recursive);
        }

        return mkdir($path, $mode, $recursive);
    }

    /**
     * Scan all file information in the specified directory.
     *
     * @param string $dir
     * @return array
     */
    public static function scanFiles(string $dir = '')
    {
        $files = [];
        $fds   = scandir($dir);
        unset($fds[array_search('.', $fds, true)]);
        unset($fds[array_search('..', $fds, true)]);

        foreach ($fds as $fd) {
            $path  = $dir . '/' . $fd;
            $farr  = is_dir($path) ? static::scanFiles($path) : [$path];
            $files = array_merge($files, $farr);
        }

        return $files;
    }


    /**
     * Write the contents of a file, replacing it atomically if it already exists.
     *
     * @param  string  $path
     * @param  string  $content
     * @return void
     */
    public static function replace($path, $content)
    {
        // If the path already exists and is a symlink, get the real path...
        clearstatcache(true, $path);

        $path = realpath($path) ?: $path;

        $tempPath = tempnam(dirname($path), basename($path));

        // Fix permissions of tempPath because `tempnam()` creates it with permissions set to 0600...
        chmod($tempPath, 0777 - umask());

        file_put_contents($tempPath, $content);

        rename($tempPath, $path);
    }
}
