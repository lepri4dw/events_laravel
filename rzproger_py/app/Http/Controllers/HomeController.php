<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Показать главную страницу.
     */
    public function index()
    {
        // Получаем ближайшие мероприятия
        $upcomingEvents = Event::where('start_datetime', '>=', Carbon::now())
            ->orderBy('start_datetime', 'asc')
            ->limit(6)
            ->get();
        
        return view('welcome', compact('upcomingEvents'));
    }
} 