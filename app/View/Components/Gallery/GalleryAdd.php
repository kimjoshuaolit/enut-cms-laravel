<?php

namespace App\View\Components\Gallery;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GalleryAdd extends Component
{
    /**
     * Create a new component instance.
     */
    public string $category;
    public function __construct(string $category)
    {
        //
        $this->category = $category;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.gallery.gallery-add');
    }
}
