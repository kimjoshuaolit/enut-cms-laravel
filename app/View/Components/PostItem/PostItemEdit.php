<?php

namespace App\View\Components\PostItem;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PostItemEdit extends Component
{
    public string $category;

    public function __construct(string $category)
    {
        $this->category = $category;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.post-item.post-item-edit');
    }
}
