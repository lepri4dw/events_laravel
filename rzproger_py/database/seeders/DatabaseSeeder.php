<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Event;
use App\Models\Comment;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create users
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'is_admin' => true,
        ]);

        $user1 = User::create([
            'name' => 'Ivan',
            'email' => 'ivan@gmail.com',
            'password' => Hash::make('admin'),
            'is_admin' => false,
        ]);

        $user2 = User::create([
            'name' => 'Maria',
            'email' => 'maria@gmail.com',
            'password' => Hash::make('admin'),
            'is_admin' => false,
        ]);

        // Create events with dates between May 25, 2025 and June 10, 2025
        $eventTypes = ['Концерт', 'Выставка', 'Семинар', 'Мастер-класс', 'Другое'];
        $locations = ['Бишкек, ул. Манаса 40', 'Бишкек, проспект Чуй 123', 'Бишкек, ул. Ахунбаева 97', 'Бишкек, ул. Киевская 44'];
        
        // Image URLs that can be used directly
        $imageUrls = [
            'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1523580494863-6f3031224c94?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80'
        ];

        $startDate = Carbon::parse('2025-05-25');
        $endDate = Carbon::parse('2025-06-10');
        $dateDifference = $startDate->diffInDays($endDate);

        $events = [
            [
                'title' => 'Концерт симфонического оркестра',
                'description' => 'Насладитесь великолепным исполнением классических произведений в исполнении симфонического оркестра Бишкека.',
                'type' => 'Концерт',
                'price' => 500,
                'duration' => 120,
            ],
            [
                'title' => 'Выставка современного искусства',
                'description' => 'Уникальная возможность увидеть работы современных художников Кыргызстана и зарубежья.',
                'type' => 'Выставка',
                'price' => 200,
                'duration' => 180,
            ],
            [
                'title' => 'Мастер-класс по фотографии',
                'description' => 'Учитесь основам фотографии от профессионалов. Программа для начинающих и опытных фотографов.',
                'type' => 'Мастер-класс',
                'price' => 300,
                'duration' => 90,
            ],
            [
                'title' => 'IT-конференция "Технологии будущего"',
                'description' => 'Узнайте о последних технологических трендах и инновациях от ведущих специалистов отрасли.',
                'type' => 'Семинар',
                'price' => 1000,
                'duration' => 360,
            ],
            [
                'title' => 'Благотворительный забег',
                'description' => 'Примите участие в благотворительном забеге. Все средства пойдут на поддержку детских домов Бишкека.',
                'type' => 'Другое',
                'price' => 0,
                'duration' => 120,
            ],
        ];

        foreach ($events as $index => $eventData) {
            // Generate random date between May 25, 2025 and June 10, 2025
            $randomDays = rand(0, $dateDifference);
            $eventDate = $startDate->copy()->addDays($randomDays);
            
            // Set random hour between 9:00 and 20:00
            $eventDate->hour(rand(9, 20));
            $eventDate->minute(0);
            
            $event = Event::create([
                'title' => $eventData['title'],
                'description' => $eventData['description'],
                'type' => $eventData['type'],
                'start_datetime' => $eventDate,
                'duration' => $eventData['duration'],
                'price' => $eventData['price'],
                'location' => $locations[array_rand($locations)],
                'image_path' => $imageUrls[$index % count($imageUrls)],
            ]);
            
            // Add some comments to the event
            Comment::create([
                'event_id' => $event->id,
                'user_id' => $user1->id,
                'content' => 'Очень интересное мероприятие! Обязательно посещу.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            Comment::create([
                'event_id' => $event->id,
                'user_id' => $user2->id,
                'content' => 'Отличное мероприятие, рекомендую всем!',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Add the event to favorites for some users
            if ($index % 2 == 0) {
                $user1->favorites()->attach($event->id);
            } else {
                $user2->favorites()->attach($event->id);
            }
        }
    }
}
