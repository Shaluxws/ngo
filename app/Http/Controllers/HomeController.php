<?php

namespace App\Http\Controllers;

use App\Services\HomepageDataService;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the dynamic database-driven NGO public homepage.
     */
    public function index(): View
    {
        $data = HomepageDataService::get();

        return view('home', $data);
    }
}
