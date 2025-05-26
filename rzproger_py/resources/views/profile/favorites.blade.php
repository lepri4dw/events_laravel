<x-app-layout>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-header">Меню профиля</div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                            <i class="bi bi-person"></i> Личные данные
                        </a>
                        <a href="{{ route('profile.favorites') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.favorites') ? 'active' : '' }}">
                            <i class="bi bi-heart"></i> Избранное
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title mb-0">Избранные мероприятия</h1>
                    </div>
                    <div class="card-body">
                        @if (count($favorites) > 0)
                            <div class="row">
                                @foreach ($favorites as $event)
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
                                                <form action="{{ route('events.unfavorite', $event) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-heart-fill"></i> Удалить
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-4">
                                {{ $favorites->links() }}
                            </div>
                        @else
                            <div class="alert alert-info">
                                <p class="mb-0">У вас нет избранных мероприятий. <a href="{{ route('events.index') }}">Найти мероприятия</a></p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 