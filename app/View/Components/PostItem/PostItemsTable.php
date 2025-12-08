<?php

namespace App\View\Components\PostItem;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\PostItem;

class PostItemsTable extends Component
{
    public $postItems;
    public string $category;
    public int $perPage;

    public function __construct(string $category, int $perPage = 10)
    {
        $this->category = $category;
        $this->perPage = $perPage;


        $this->postItems = PostItem::select(
            'id',
            'post_title',
            'post_description',
            'post_survey',
            'post_year',
            'pic_file',
            'pdf_path',
            'created_at'
        )
            ->where('post_cat', $category)
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }

    public function getImageUrl($path): string
    {
        if (!$path) {
            return '';
        }

        $newPath = 'enutV2/' . $path;
        return asset($newPath);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.post-item.post-items-table');
    }
}
