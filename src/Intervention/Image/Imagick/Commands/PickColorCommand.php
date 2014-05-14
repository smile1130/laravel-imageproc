<?php

namespace Intervention\Image\Imagick\Commands;

use \Intervention\Image\Imagick\Color;

class PickColorCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $x = $this->argument(0)->type('integer')->required()->value();
        $y = $this->argument(1)->type('integer')->required()->value();
        $format = $this->argument(2)->type('string')->value('array');

        // pick color
        $color = new Color($image->getCore()->getImagePixelColor($x, $y));

        // format to output
        $this->setOutput($color->format($format));

        return true;
    }
}
