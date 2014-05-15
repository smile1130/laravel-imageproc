<?php

namespace Intervention\Image\Imagick\Commands;

use \Intervention\Image\Imagick\Source;

class InsertCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Insert another image into given image
     *
     * @param  Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $source = $this->argument(0)->value();
        $position = $this->argument(1)->type('string')->value();
        $x = $this->argument(2)->type('integer')->value(0);
        $y = $this->argument(3)->type('integer')->value(0);

        // build watermark
        $watermark = $image->getDriver()->init($source);

        // define insertion point
        $image_size = $image->getSize()->align($position, $x, $y);
        $watermark_size = $watermark->getSize()->align($position);
        $target = $image_size->relativePosition($watermark_size);

        // insert image at position
        return $image->getCore()->compositeImage($watermark->getCore(), \Imagick::COMPOSITE_DEFAULT, $target->x, $target->y);
    }
}
