<?php

namespace Intervention\Image\Gd\Commands;

use \Intervention\Image\Gd\Color;

class PickColorCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $x = $this->argument(0)->type('integer')->required()->value();
        $y = $this->argument(1)->type('integer')->required()->value();
        $format = $this->argument(2)->type('string')->value('array');

        // pick color
        $color = imagecolorat($image->getCore(), $x, $y);

        if ( ! imageistruecolor($image->getCore())) {
            $color = imagecolorsforindex($image->getCore(), $color);    
            $color['alpha'] = round(1 - $color['alpha'] / 127, 2);
        }

        $color = new Color($color);

        // format to output
        $this->setOutput($color->format($format));

        return true;
    }
}
