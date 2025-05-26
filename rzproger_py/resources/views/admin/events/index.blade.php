@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Управление мероприятиями</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.events.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg"></i> Создать мероприятие
            </a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col" width="60">#</th>
                            <th scope="col">Изображение</th>
                            <th scope="col">Название</th>
                            <th scope="col">Тип</th>
                            <th scope="col">Дата</th>
                            <th scope="col">Локация</th>
                            <th scope="col">Цена</th>
                            <th scope="col" width="120">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            <tr>
                                <td>{{ $event->id }}</td>
                                <td>
                                    @if($event->image_path)
                                        @if(strpos($event->image_path, 'http') === 0)
                                            <img src="{{ $event->image_path }}" alt="{{ $event->title }}" class="img-thumbnail" style="height: 50px;">
                                        @else
                                            <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="img-thumbnail" style="height: 50px;">
                                        @endif
                                    @else
                                        <span class="text-muted">Нет изображения</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('events.show', $event) }}" target="_blank">
                                        {{ $event->title }}
                                    </a>
                                </td>
                                <td>{{ $event->type }}</td>
                                <td>{{ $event->start_datetime->format('d.m.Y H:i') }}</td>
                                <td>{{ Str::limit($event->location, 30) }}</td>
                                <td>
                                    @if($event->price > 0)
                                        {{ number_format($event->price, 0, '.', ' ') }} сом
                                    @else
                                        <span class="badge bg-success">Бесплатно</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-warning me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить это мероприятие?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="mb-0">Мероприятия не найдены</p>
                                    <a href="{{ route('admin.events.create') }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-plus-lg"></i> Создать мероприятие
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $events->links() }}
    </div>
@endsection 