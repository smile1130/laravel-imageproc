<?php

namespace Intervention\Image\Gd\Commands;

use \Intervention\Image\Point;
use \Intervention\Image\Size;

class ResizeCanvasCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $width = $this->getArgument(0);
        $height = $this->getArgument(1);
        $anchor = $this->getArgument(2) ? $this->getArgument(2) : 'center';
        $relative = $this->getArgument(3);
        $bgcolor = $this->getArgument(4);

        $original_width = $image->getWidth();
        $original_height = $image->getHeight();

        // check of only width or height is set
        $width = is_null($width) ? $original_width : intval($width);
        $height = is_null($height) ? $original_height : intval($height);
        
        // check on relative width/height
        if ($relative) {
            $width = $original_width + $width;
            $height = $original_height + $height;
        }

        // check for negative width/height
        $width = ($width <= 0) ? $width + $original_width : $width;
        $height = ($height <= 0) ? $height + $original_height : $height;
    
        // create new canvas        
        $canvas = $image->getDriver()->newImage($width, $height, $bgcolor);

        // set copy position
        $canvas_size = $canvas->getSize()->align($anchor);
        $image_size = $image->getSize()->align($anchor);
        $canvas_pos = $image_size->relativePosition($canvas_size);
        $image_pos = $canvas_size->relativePosition($image_size);
        
        if ($width <= $original_width) {
            $dst_x = 0;
            $src_x = $canvas_pos->x;
            $src_w = $canvas_size->width;
        } else {
            $dst_x = $image_pos->x;
            $src_x = 0;
            $src_w = $original_width;
        }

        if ($height <= $original_height) {
            $dst_y = 0;
            $src_y = $canvas_pos->y;
            $src_h = $canvas_size->height;   
        } else {
            $dst_y = $image_pos->y;
            $src_y = 0;
            $src_h = $original_height;
        }

        // make image area transparent to keep transparency
        // even if background-color is set
        $transparent = imagecolorallocatealpha($canvas->getCore(), 0, 0, 0, 127);
        imagealphablending($canvas->getCore(), false); // do not blend / just overwrite
        imagefilledrectangle($canvas->getCore(), $dst_x, $dst_y, $src_w + 1, $src_h + 1, $transparent);

        // copy image into new canvas
        imagecopy($canvas->getCore(), $image->getCore(), $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);

        // set new core to canvas
        $image->setCore($canvas->getCore());

        return true;
    }
}
