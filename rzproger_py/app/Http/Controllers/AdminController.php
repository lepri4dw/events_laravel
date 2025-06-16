<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $eventsCount = Event::count();
        $usersCount = User::count();
        $commentsCount = Comment::count();
        
        $latestEvents = Event::latest()->take(5)->get();
        $latestUsers = User::latest()->take(5)->get();
        $latestComments = Comment::with(['user', 'event'])->latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'eventsCount', 
            'usersCount', 
            'commentsCount', 
            'latestEvents', 
            'latestUsers', 
            'latestComments'
        ));
    }

    /**
     * Display a listing of events.
     *
     * @return \Illuminate\View\View
     */
    public function events()
    {
        $events = Event::orderBy('start_datetime')->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     *
     * @return \Illuminate\View\View
     */
    public function createEvent()
    {
        return view('admin.events.form');
    }

    /**
     * Store a newly created event in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeEvent(Request $request)
    {
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
            'image_url' => 'nullable|url',
        ]);
        
        $event = new Event();
        $event->title = $validated['title'];
        $event->description = $validated['description'];
        $event->type = $validated['type'];
        $event->location = $validated['location'];
        
        // Combine date and time
        $start_datetime = Carbon::parse($validated['start_date'] . ' ' . $validated['start_time']);
        $event->start_datetime = $start_datetime;
        
        $event->duration = $validated['duration'];
        $event->price = $validated['price'];
        
        // Handle image upload or URL
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $event->image_path = $path;
        } elseif ($request->filled('image_url')) {
            $event->image_path = $request->image_url;
        }
        
        $event->save();
        
        return redirect()->route('admin.events')->with('success', 'Мероприятие успешно создано!');
    }

    /**
     * Show the form for editing the specified event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\View\View
     */
    public function editEvent(Event $event)
    {
        // Convert start_datetime back to date and time for the form
        $event->start_date = $event->start_datetime->format('Y-m-d');
        $event->start_time = $event->start_datetime->format('H:i');
        
        return view('admin.events.form', compact('event'));
    }

    /**
     * Update the specified event in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateEvent(Request $request, Event $event)
    {
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
            'image_url' => 'nullable|url',
        ]);
        
        $event->title = $validated['title'];
        $event->description = $validated['description'];
        $event->type = $validated['type'];
        $event->location = $validated['location'];
        
        // Combine date and time
        $start_datetime = Carbon::parse($validated['start_date'] . ' ' . $validated['start_time']);
        $event->start_datetime = $start_datetime;
        
        $event->duration = $validated['duration'];
        $event->price = $validated['price'];
        
        // Handle image upload or URL
        if ($request->hasFile('image')) {
            // Delete old image if it exists and is not a URL
            if ($event->image_path && strpos($event->image_path, 'http') !== 0) {
                Storage::disk('public')->delete($event->image_path);
            }
            
            $path = $request->file('image')->store('events', 'public');
            $event->image_path = $path;
        } elseif ($request->filled('image_url')) {
            // If old image is from storage, delete it
            if ($event->image_path && strpos($event->image_path, 'http') !== 0) {
                Storage::disk('public')->delete($event->image_path);
            }
            
            $event->image_path = $request->image_url;
        }
        
        $event->save();
        
        return redirect()->route('admin.events')->with('success', 'Мероприятие успешно обновлено!');
    }

    /**
     * Remove the specified event from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyEvent(Event $event)
    {
        // Delete image if it exists and is not a URL
        if ($event->image_path && strpos($event->image_path, 'http') !== 0) {
            Storage::disk('public')->delete($event->image_path);
        }
        
        $event->delete();
        
        return redirect()->route('admin.events')->with('success', 'Мероприятие успешно удалено!');
    }

    /**
     * Display a listing of users.
     *
     * @return \Illuminate\View\View
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateUser(Request $request, User $user)
    {
        // Проверяем, не пытается ли админ снять права у самого себя
        if ($user->id === auth()->id() && !$request->has('is_admin')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Вы не можете снять права администратора у самого себя.',
                    'is_admin' => true
                ]);
            }
            return redirect()
                ->back()
                ->with('error', 'Вы не можете снять права администратора у самого себя.');
        }

        // Обновляем статус администратора
        $user->is_admin = $request->has('is_admin');
        $user->save();

        $status = $user->is_admin ? 'назначен администратором' : 'больше не является администратором';
        $message = "Пользователь {$user->name} {$status}.";
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_admin' => $user->is_admin
            ]);
        }

        return redirect()
            ->route('admin.users')
            ->with('success', $message);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyUser(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Вы не можете удалить собственный аккаунт!');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users')->with('success', 'Пользователь успешно удален!');
    }

    /**
     * Display a listing of comments.
     *
     * @return \Illuminate\View\View
     */
    public function comments()
    {
        $comments = Comment::with(['user', 'event'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Remove the specified comment from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyComment(Comment $comment)
    {
        $comment->delete();
        
        return redirect()->route('admin.comments')->with('success', 'Комментарий успешно удален!');
    }

    /**
     * Display the clear database interface.
     *
     * @return \Illuminate\View\View
     */
    public function clearDatabase()
    {
        return view('admin.clear-database');
    }
    
    /**
     * Clear the specified database table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearTable(Request $request)
    {
        $validated = $request->validate([
            'table' => 'required|string|in:events,comments,favorites,all',
            'confirm' => 'required|string|in:DELETE'
        ]);
        
        $successMessage = '';
        
        switch ($validated['table']) {
            case 'events':
                // First, delete all images
                $events = Event::all();
                foreach ($events as $event) {
                    if ($event->image_path && strpos($event->image_path, 'http') !== 0) {
                        Storage::disk('public')->delete($event->image_path);
                    }
                }
                
                // Delete records from associated tables
                \DB::table('comments')->delete();
                \DB::table('favorites')->delete();
                \DB::table('events')->delete();
                
                $successMessage = 'Все мероприятия и связанные данные были успешно удалены!';
                break;
                
            case 'comments':
                \DB::table('comments')->delete();
                $successMessage = 'Все комментарии были успешно удалены!';
                break;
                
            case 'favorites':
                \DB::table('favorites')->delete();
                $successMessage = 'Все избранные мероприятия были успешно удалены!';
                break;
                
            case 'all':
                // First, delete all images
                $events = Event::all();
                foreach ($events as $event) {
                    if ($event->image_path && strpos($event->image_path, 'http') !== 0) {
                        Storage::disk('public')->delete($event->image_path);
                    }
                }
                
                // Clear all tables except users
                \DB::table('comments')->delete();
                \DB::table('favorites')->delete();
                \DB::table('events')->delete();
                
                $successMessage = 'Все таблицы данных (кроме пользователей) были успешно очищены!';
                break;
        }
        
        return redirect()->route('admin.clear-database')->with('success', $successMessage);
    }
} 