<?php

namespace App\View\Components\Gallery;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Gallery;

class GalleryTable extends Component
{
    /**
     * Create a new component instance.
     */

    public $galleryItem;
    public string $category;
    public function __construct(string $category)
    {




        $this->category = $category;
        $this->galleryItem = Gallery::select(
            'id',
            'title',
            'area',
            'page_no',
            'cat_title',
            'cat_year',
            'file_path',
        )
            // ->where('category', $category)
            ->orderBy('id', 'desc')
            ->get();
    }


    public function render(): View|Closure|string
    {
        return view('components.gallery.gallery-table');
    }
}
