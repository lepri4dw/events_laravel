@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Панель управления</h1>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
        <div class="col">
            <div class="card h-100 border-primary">
                <div class="card-body d-flex">
                    <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-3" style="width: 4rem; height: 4rem;">
                        <i class="bi bi-calendar-event fs-1 text-primary"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="card-title">Мероприятия</h5>
                        <h2 class="card-text mb-0">{{ $eventsCount }}</h2>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.events') }}" class="btn btn-sm btn-outline-primary">Управление мероприятиями</a>
                </div>
            </div>
        </div>
        
        <div class="col">
            <div class="card h-100 border-success">
                <div class="card-body d-flex">
                    <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-3" style="width: 4rem; height: 4rem;">
                        <i class="bi bi-people fs-1 text-success"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="card-title">Пользователи</h5>
                        <h2 class="card-text mb-0">{{ $usersCount }}</h2>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-success">Управление пользователями</a>
                </div>
            </div>
        </div>
        
        <div class="col">
            <div class="card h-100 border-warning">
                <div class="card-body d-flex">
                    <div class="d-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-3" style="width: 4rem; height: 4rem;">
                        <i class="bi bi-chat-dots fs-1 text-warning"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="card-title">Комментарии</h5>
                        <h2 class="card-text mb-0">{{ $commentsCount }}</h2>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.comments') }}" class="btn btn-sm btn-outline-warning">Управление комментариями</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Последние мероприятия</h5>
                    <a href="{{ route('admin.events') }}" class="btn btn-sm btn-outline-primary">Все мероприятия</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Дата</th>
                                    <th>Тип</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestEvents as $event)
                                    <tr>
                                        <td>
                                            <a href="{{ route('events.show', $event) }}" target="_blank">
                                                {{ Str::limit($event->title, 30) }}
                                            </a>
                                        </td>
                                        <td>{{ $event->start_datetime->format('d.m.Y H:i') }}</td>
                                        <td>{{ $event->type }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Нет мероприятий</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Последние пользователи</h5>
                    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">Все пользователи</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Имя</th>
                                    <th>Email</th>
                                    <th>Статус</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestUsers as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->isAdmin())
                                                <span class="badge bg-danger">Администратор</span>
                                            @else
                                                <span class="badge bg-secondary">Пользователь</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Нет пользователей</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Последние комментарии</h5>
                    <a href="{{ route('admin.comments') }}" class="btn btn-sm btn-outline-primary">Все комментарии</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Пользователь</th>
                                    <th>Мероприятие</th>
                                    <th>Комментарий</th>
                                    <th>Дата</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestComments as $comment)
                                    <tr>
                                        <td>{{ $comment->user->name }}</td>
                                        <td>
                                            <a href="{{ route('events.show', $comment->event) }}" target="_blank">
                                                {{ Str::limit($comment->event->title, 30) }}
                                            </a>
                                        </td>
                                        <td>{{ Str::limit($comment->content, 50) }}</td>
                                        <td>{{ $comment->created_at->format('d.m.Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Нет комментариев</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 