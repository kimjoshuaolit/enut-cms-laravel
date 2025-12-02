<?php

namespace App\View\Components\enutrition;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Gallery;
use App\Models\PostItem;
use App\Models\Enns;
use App\Models\Logs;
use Illuminate\Support\Facades\Cache;


class eNutritionMetrics extends Component
{
    public string $currentDate;
    public int $totalRows;
    public int $totalVisits;


    public function __construct()
    {
        $this->currentDate = date('m-d-Y');

        $this->totalRows = Gallery::count() +
            PostItem::count() +
            Enns::count();

        $this->totalVisits = Cache::remember('metrics.total_visit', 600, function () {
            return Logs::where('description', 'like', 'Login to his/her account%')
                ->count();
        });
    }
    public function render(): View|Closure|string
    {
        return view('components.enutrition.enutrition-metrics');
    }
}
