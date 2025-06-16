<x-app-layout>
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1>Мероприятия</h1>
                <p class="text-muted">Найдите интересующие вас мероприятия</p>
            </div>
            <div class="col-md-4 text-md-end">
                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('events.create') }}" class="btn btn-primary">Создать мероприятие</a>
                    @endif
                @endauth
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-header">
                        Фильтры
                    </div>
                    <div class="card-body">
                        <form action="{{ route('events.index') }}" method="GET">
                            <div class="mb-3">
                                <label for="type" class="form-label">Тип мероприятия</label>
                                <select name="type" id="type" class="form-select">
                                    <option value="">Все типы</option>
                                    <option value="Концерт" {{ request('type') == 'Концерт' ? 'selected' : '' }}>Концерт</option>
                                    <option value="Выставка" {{ request('type') == 'Выставка' ? 'selected' : '' }}>Выставка</option>
                                    <option value="Семинар" {{ request('type') == 'Семинар' ? 'selected' : '' }}>Семинар</option>
                                    <option value="Мастер-класс" {{ request('type') == 'Мастер-класс' ? 'selected' : '' }}>Мастер-класс</option>
                                    <option value="Другое" {{ request('type') == 'Другое' ? 'selected' : '' }}>Другое</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">Дата</label>
                                <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
                            </div>

                            <button type="submit" class="btn btn-primary">Применить</button>
                            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">Сбросить</a>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="row">
                    @forelse ($events as $event)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                @if ($event->image_path)
                                    @if (strpos($event->image_path, 'http') === 0)
                                        <img src="{{ $event->image_path }}" class="card-img-top" alt="{{ $event->title }}">
                                    @else
                                        <img src="{{ asset('storage/' . $event->image_path) }}" class="card-img-top" alt="{{ $event->title }}">
                                    @endif
                                @else
                                    <div class="bg-light text-center py-5">
                                        <span class="text-muted">Нет изображения</span>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $event->title }}</h5>
                                    <p class="card-text text-muted mb-1">
                                        <small>
                                            <i class="bi bi-calendar"></i> {{ $event->start_datetime->format('d.m.Y H:i') }}
                                        </small>
                                    </p>
                                    <p class="card-text text-muted mb-2">
                                        <small>
                                            <i class="bi bi-geo-alt"></i> {{ $event->location }}
                                        </small>
                                    </p>
                                    <p class="card-text">{{ \Illuminate\Support\Str::limit($event->description, 100) }}</p>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-primary">Подробнее</a>
                                    
                                    @auth
                                        @if (auth()->user()->hasFavorited($event))
                                            <form action="{{ route('events.unfavorite', $event) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-heart-fill"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('events.favorite', $event) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-heart"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                Мероприятия не найдены. 
                                @auth
                                    @if(auth()->user()->is_admin)
                                        <a href="{{ route('events.create') }}">Создать мероприятие</a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 