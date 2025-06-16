<x-app-layout>
    <div class="container py-4">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Мероприятия</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $event->title }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    @if ($event->image_path)
                        @if (strpos($event->image_path, 'http') === 0)
                            <img src="{{ $event->image_path }}" class="card-img-top" alt="{{ $event->title }}">
                        @else
                            <img src="{{ asset('storage/' . $event->image_path) }}" class="card-img-top" alt="{{ $event->title }}">
                        @endif
                    @endif
                    <div class="card-body">
                        <h1 class="card-title">{{ $event->title }}</h1>
                        <p class="text-muted mb-3">
                            <span class="me-3"><i class="bi bi-calendar"></i> {{ $event->start_datetime->format('d.m.Y H:i') }}</span>
                            <span class="me-3"><i class="bi bi-geo-alt"></i> {{ $event->location }}</span>
                            <span><i class="bi bi-tag"></i> {{ $event->type }}</span>
                        </p>
                        
                        <div class="mb-3">
                            <h5>Описание</h5>
                            <p>{{ $event->description }}</p>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                @if ($event->price > 0)
                                    <h4 class="text-primary">{{ number_format($event->price, 0, '.', ' ') }} сом</h4>
                                @else
                                    <span class="badge bg-success">Бесплатно</span>
                                @endif
                            </div>
                            <div>
                                @auth
                                    @if(auth()->user()->is_admin)
                                        <a href="{{ route('events.edit', $event) }}" class="btn btn-warning me-2">
                                            <i class="bi bi-pencil"></i> Редактировать
                                        </a>
                                        <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены?');">
                                                <i class="bi bi-trash"></i> Удалить
                                            </button>
                                        </form>
                                    @endif

                                    @if (auth()->user()->hasFavorited($event))
                                        <form action="{{ route('events.unfavorite', $event) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-heart-fill"></i> Удалить из избранного
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('events.favorite', $event) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-heart"></i> В избранное
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Комментарии -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Комментарии ({{ $event->comments->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @auth
                            <form action="{{ route('comments.store') }}" method="POST" class="mb-4">
                                @csrf
                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                <div class="mb-3">
                                    <textarea name="content" rows="3" class="form-control @error('content') is-invalid @enderror" placeholder="Оставьте комментарий..."></textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Отправить</button>
                            </form>
                        @else
                            <div class="alert alert-info">
                                <a href="{{ route('login') }}">Войдите</a> или <a href="{{ route('register') }}">зарегистрируйтесь</a>, чтобы оставить комментарий.
                            </div>
                        @endauth

                        @forelse ($event->comments()->latest()->get() as $comment)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="card-subtitle">{{ $comment->user->name }}</h6>
                                        <small class="text-muted">{{ $comment->created_at->format('d.m.Y H:i') }}</small>
                                    </div>
                                    <p class="card-text">{{ $comment->content }}</p>
                                    @auth
                                        @if (auth()->user()->id == $comment->user_id || auth()->user()->isAdmin())
                                            <div class="mt-2">
                                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Вы уверены?')">
                                                        <i class="bi bi-trash"></i> Удалить
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-3">
                                <p class="text-muted">Комментариев пока нет. Будьте первым!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Информация о мероприятии</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i class="bi bi-calendar"></i> Дата и время</span>
                                <span>{{ $event->start_datetime->format('d.m.Y H:i') }}</span>
                            </li>
                            @if ($event->duration)
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i class="bi bi-hourglass"></i> Продолжительность</span>
                                <span>{{ $event->duration }} мин.</span>
                            </li>
                            @endif
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i class="bi bi-geo-alt"></i> Место</span>
                                <span>{{ $event->location }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i class="bi bi-tag"></i> Тип</span>
                                <span>{{ $event->type }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i class="bi bi-currency-dollar"></i> Стоимость</span>
                                @if ($event->price > 0)
                                    <span>{{ number_format($event->price, 0, '.', ' ') }} сом</span>
                                @else
                                    <span class="badge bg-success">Бесплатно</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Организатор</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Администратор сайта</strong></p>
                        <p><i class="bi bi-envelope"></i> admin@example.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 