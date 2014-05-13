<?php

namespace Intervention\Image\Imagick\Commands;

class PixelateCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $size = intval($this->getArgument(0, 10));

        $width = $image->getWidth();
        $height = $image->getHeight();

        $image->getCore()->scaleImage(max(1, ($width / $size)), max(1, ($height / $size)));
        $image->getCore()->scaleImage($width, $height);

        return true;
    }
}
