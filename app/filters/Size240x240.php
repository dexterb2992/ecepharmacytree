<?php
namespace Intervention\Image\Templates;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Size240x240 implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(240, 240);
    }
}