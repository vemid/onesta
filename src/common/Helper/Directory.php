<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Helper;

/**
 * Class Directory
 * @package Vemid\ProjectOne\Common\Helper
 */
class Directory
{
    /**
     * @param $dir
     * @param null $pattern
     * @param bool $recursively
     * @return array
     */
    public static function listFiles($dir, $pattern = null, $recursively = true)
    {
        return static::scanDir($dir, $pattern, $recursively);
    }

    /**
     * @param $dir
     * @param bool $recursively
     * @return array
     */
    public static function listClasses($dir, $recursively = true): array
    {
        $classes = [];
        $files = static::scanDir($dir, '/.+\.php/i', $recursively);

        foreach ($files as $filePath) {
            $className = static::getFullyQualifiedClassName($filePath);
            if ($className !== null) {
                $classes[] = $className;
            }
        }

        return $classes;
    }

    /**
     * @param string $dir
     * @param null|string $pattern
     * @param bool $recursively
     * @return array
     */
    protected static function scanDir($dir, $pattern = null, $recursively = true): array
    {
        $dir = realpath(rtrim($dir, DIRECTORY_SEPARATOR));
        $directoryContent = scandir($dir);
        $files = [];

        if (empty($directoryContent)) {
            return $files;
        }

        foreach ($directoryContent as $contentName) {
            if ($contentName === '.' || $contentName === '..') {
                continue;
            }

            if (is_file($filename = $dir . DIRECTORY_SEPARATOR . $contentName)) {
                if ($pattern === null) {
                    $files[] = $filename;
                    continue;
                }

                if (preg_match($pattern, $contentName)) {
                    $files[] = $filename;
                    continue;
                }
            }

            if ($recursively && is_dir($subDir = ($dir . DIRECTORY_SEPARATOR . $contentName))) {
                $files = array_merge($files, static::scanDir($subDir, $pattern, $recursively));
                continue;
            }
        }

        return $files;
    }

    /**
     * @param $filename
     * @return string|null
     */
    protected static function getFullyQualifiedClassName($filename)
    {
        $namespace = '';
        $tokens = token_get_all(file_get_contents($filename));

        $count = count($tokens);
        for ($i = 0; $i < $count; ++$i) {
            if ($tokens[$i][0] === T_NAMESPACE) {
                for ($j = $i + 1; $j < $count; ++$j) {
                    if ($tokens[$j][0] === T_STRING) {
                        $namespace .= '\\' . $tokens[$j][1];
                    } elseif ($tokens[$j] === '{' || $tokens[$j] === ';') {
                        break;
                    }
                }
            }

            if ($tokens[$i][0] === T_CLASS) {
                for ($j = $i + 1; $j < $count; ++$j) {
                    if ($tokens[$j] === '{') {
                        if ($class = $tokens[$i + 2][1]) {
                            return $namespace . '\\' . $class;
                        }
                    }
                }
            }
        }

        return null;
    }
}
