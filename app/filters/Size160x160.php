<?php
namespace Intervention\Image\Templates;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Size160x160 implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(160, 160);
    }
}