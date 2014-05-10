<?php

namespace Intervention\Image\Gd\Commands;

class OpacityCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $transparency = $this->getArgument(0);

        // get size of image
        $size = $image->getSize();

        // build temp alpha mask
        $mask_color = sprintf('rgba(0, 0, 0, %.1f)', $transparency / 100);
        $mask = $image->getDriver()->newImage($size->width, $size->height, $mask_color);

        // mask image
        $image->mask($mask->getCore(), true);

        return true;
    }
}
