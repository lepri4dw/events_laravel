<x-app-layout>
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="jumbotron bg-light p-5 rounded">
                    <h1 class="display-4">Добро пожаловать на Events Management!</h1>
                    <p class="lead">Найдите интересные мероприятия в вашем городе или создайте свои собственные.</p>
                    <hr class="my-4">
                    <p>Присоединяйтесь к нам сегодня и никогда не пропускайте интересные события!</p>
                    <a class="btn btn-primary btn-lg" href="{{ route('events.index') }}" role="button">Смотреть все мероприятия</a>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h2>Ближайшие мероприятия</h2>
            </div>
        </div>

        <div class="row mt-3">
            @forelse($upcomingEvents as $event)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        @if($event->image_path)
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
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if($event->price > 0)
                                        <span class="text-primary fw-bold">{{ number_format($event->price, 0, '.', ' ') }} сом</span>
                                    @else
                                        <span class="badge bg-success">Бесплатно</span>
                                    @endif
                                </div>
                                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-primary">Подробнее</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        Нет предстоящих мероприятий.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('events.index') }}" class="btn btn-outline-primary">Смотреть все мероприятия</a>
            </div>
        </div>
    </div>
</x-app-layout>
