<?php

namespace App;

class Tools
{
    private static array $imagesType = [
        "image/png",
        "image/webp",
        "image/jpeg",
        "image/svg+xml",
        "image/apng", "image/gif",
        "image/avif",
        "image/tiff"
    ];
    

    /**
     * Get the value of imagesType
     */ 
    public static function getImagesType(): array
    {
        return self::$imagesType;
    }
}
