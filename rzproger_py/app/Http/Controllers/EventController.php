<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EventController extends Controller
{
    public function __construct()
    {
        // Apply admin middleware to admin-only actions
        $this->middleware('admin')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Показать список всех мероприятий.
     */
    public function index(Request $request)
    {
        $query = Event::query();
        
        // Фильтрация по типу
        if ($request->has('type') && $request->type) {
            // Debug the type value being used for filtering
            \Log::info('Filtering by type: ' . $request->type);
            
            $query->where('type', $request->type);
        }
        
        // Фильтрация по дате
        if ($request->has('date') && $request->date) {
            $date = Carbon::parse($request->date);
            $query->whereDate('start_datetime', $date->format('Y-m-d'));
        }
        
        // Сортировка по дате начала (ближайшие сначала)
        $query->orderBy('start_datetime', 'asc');
        
        $events = $query->paginate(9);
        
        // Ensure pagination preserves query parameters
        $events->appends($request->all());
        
        return view('events.index', compact('events'));
    }

    /**
     * Показать форму для создания нового мероприятия.
     */
    public function create()
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        return view('events.form');
    }

    /**
     * Сохранить новое мероприятие в базе данных.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'type' => 'required',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'location' => 'required|max:255',
            'image' => 'nullable|image|max:2048',
        ]);
        
        $event = new Event();
        $event->title = $validated['title'];
        $event->description = $validated['description'];
        $event->type = $validated['type'];
        $event->location = $validated['location'];
        
        // Объединяем дату и время
        $start_datetime = Carbon::parse($validated['start_date'] . ' ' . $validated['start_time']);
        $event->start_datetime = $start_datetime;
        
        $event->duration = $validated['duration'];
        $event->price = $validated['price'];
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $event->image_path = $path;
        }
        
        $event->save();
        
        return redirect()->route('events.show', $event)->with('success', 'Мероприятие успешно создано!');
    }

    /**
     * Показать указанное мероприятие.
     */
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    /**
     * Показать форму для редактирования указанного мероприятия.
     */
    public function edit(Event $event)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        // Преобразуем start_datetime обратно в дату и время для формы
        $event->start_date = $event->start_datetime->format('Y-m-d');
        $event->start_time = $event->start_datetime->format('H:i');
        
        return view('events.form', compact('event'));
    }

    /**
     * Обновить указанное мероприятие в базе данных.
     */
    public function update(Request $request, Event $event)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'type' => 'required',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'location' => 'required|max:255',
            'image' => 'nullable|image|max:2048',
        ]);
        
        $event->title = $validated['title'];
        $event->description = $validated['description'];
        $event->type = $validated['type'];
        $event->location = $validated['location'];
        
        // Объединяем дату и время
        $start_datetime = Carbon::parse($validated['start_date'] . ' ' . $validated['start_time']);
        $event->start_datetime = $start_datetime;
        
        $event->duration = $validated['duration'];
        $event->price = $validated['price'];
        
        // Обработка изображения
        if ($request->hasFile('image')) {
            // Удаляем старое изображение, если оно существует
            if ($event->image_path) {
                Storage::disk('public')->delete($event->image_path);
            }
            
            $path = $request->file('image')->store('events', 'public');
            $event->image_path = $path;
        }
        
        $event->save();
        
        return redirect()->route('events.show', $event)->with('success', 'Мероприятие успешно обновлено!');
    }

    /**
     * Удалить указанное мероприятие из базы данных.
     */
    public function destroy(Event $event)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        // Удаляем изображение, если оно существует
        if ($event->image_path) {
            Storage::disk('public')->delete($event->image_path);
        }
        
        $event->delete();
        
        return redirect()->route('events.index')->with('success', 'Мероприятие успешно удалено!');
    }
    
    /**
     * Добавить мероприятие в избранное.
     */
    public function favorite(Event $event)
    {
        $user = Auth::user();
        
        if (!$user->hasFavorited($event)) {
            $user->favorites()->attach($event->id);
        }
        
        return redirect()->back()->with('success', 'Мероприятие добавлено в избранное!');
    }
    
    /**
     * Удалить мероприятие из избранного.
     */
    public function unfavorite(Event $event)
    {
        $user = Auth::user();
        
        if ($user->hasFavorited($event)) {
            $user->favorites()->detach($event->id);
        }
        
        return redirect()->back()->with('success', 'Мероприятие удалено из избранного!');
    }
} 