<?php

namespace App\Http\ViewComposers;

use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class CategoryComposer
{
    public function compose(View $view)
    {
        // Cache categories for 1 hour
        $categories = Cache::remember('categories.all', 3600, function () {
            return Category::withCount(['products' => function($query) {
                $query->where('status', 'approved')
                     ->where('is_sold', false);
            }])->get();
        });

        $view->with('categories', $categories);
    }
}
