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
    public int $perPage;
    public function __construct(string $category, int $perPage = 10)
    {




        $this->category = $category;
        $this->perPage = $perPage;

        $this->galleryItem = Gallery::orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }


    public function render(): View|Closure|string
    {
        return view('components.gallery.gallery-table');
    }
}
