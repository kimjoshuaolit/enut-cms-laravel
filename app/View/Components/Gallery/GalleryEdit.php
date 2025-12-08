<?php

namespace App\View\Components\Gallery;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GalleryEdit extends Component
{
    public string $category;

    public function __construct(string $category)
    {
        $this->category = $category;
    }

    public function render(): View|Closure|string
    {
        return view('components.gallery.gallery-edit');
    }
}
