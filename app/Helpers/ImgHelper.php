<?php


namespace App\Helpers;


class ImgHelper
{
    public static function base64ToImg($base64String, $outputFile)
    {
        $regex = '/data\:image\/(.+?)\;base64\,/';
        if (!preg_match($regex, $base64String, $matches)) return false;

        $image = preg_replace($regex, '', $base64String);
        $image = str_replace(' ', '+', $image);
        return file_put_contents($outputFile, base64_decode($image));
    }
}
