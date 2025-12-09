<?php

namespace App\View\Components\Gallery;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Gallery;

class GalleryTable extends Component
{
    public $galleryItem;
    public string $category;
    public int $perPage;

    public function __construct(string $category = '', int $perPage = 10)
    {
        $this->category = $category;
        $this->perPage = $perPage;

        // Build query
        $query = Gallery::query();

        // Only filter by category if provided and not empty
        if (!empty($category) && $category !== 'Infographics') {
            $query->where('cat_title', $category);
        }

        $this->galleryItem = $query
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);
    }

    public function render(): View|Closure|string
    {
        return view('components.gallery.gallery-table');
    }
}
