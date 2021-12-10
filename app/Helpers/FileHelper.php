<?php


namespace App\Helpers;


class FileHelper
{
    public static function countLines($filePath)
    {
        $lineCount = 0;
        $handle = fopen($filePath, "r");
        while (!feof($handle)) {
            $line = fgets($handle);
            $lineCount++;
        }
        fclose($handle);
        return $lineCount;
    }
}
