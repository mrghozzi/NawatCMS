<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

final class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin::dashboard', [
            'stats' => [
                'posts' => 0,
                'pages' => 0,
                'users' => 1,
            ],
        ]);
    }
}
