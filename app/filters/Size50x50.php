<?php
namespace Intervention\Image\Templates;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Size50x50 implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(50, 50);
    }
}