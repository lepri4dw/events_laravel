<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Добавить событие в избранное.
     */
    public function store(Event $event)
    {
        $user = Auth::user();
        
        if (!$user->hasFavorited($event)) {
            $user->favorites()->attach($event->id);
        }
        
        return back()->with('success', 'Мероприятие добавлено в избранное.');
    }

    /**
     * Удалить мероприятие из избранного.
     */
    public function destroy(Event $event)
    {
        $user = Auth::user();
        
        if ($user->hasFavorited($event)) {
            $user->favorites()->detach($event->id);
        }
        
        return back()->with('success', 'Мероприятие удалено из избранного.');
    }
} 