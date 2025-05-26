@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ isset($event) ? 'Редактировать мероприятие' : 'Создать мероприятие' }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.events') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Назад к списку
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ isset($event) ? route('admin.events.update', $event) : route('admin.events.store') }}" enctype="multipart/form-data">
                @csrf
                @if(isset($event))
                    @method('PUT')
                @endif

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="title" class="form-label">Название <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $event->title ?? '') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="type" class="form-label">Тип мероприятия <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Выберите тип</option>
                                <option value="Концерт" {{ old('type', $event->type ?? '') == 'Концерт' ? 'selected' : '' }}>Концерт</option>
                                <option value="Выставка" {{ old('type', $event->type ?? '') == 'Выставка' ? 'selected' : '' }}>Выставка</option>
                                <option value="Семинар" {{ old('type', $event->type ?? '') == 'Семинар' ? 'selected' : '' }}>Семинар</option>
                                <option value="Мастер-класс" {{ old('type', $event->type ?? '') == 'Мастер-класс' ? 'selected' : '' }}>Мастер-класс</option>
                                <option value="Другое" {{ old('type', $event->type ?? '') == 'Другое' ? 'selected' : '' }}>Другое</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="location" class="form-label">Место проведения <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $event->location ?? '') }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="start_date" class="form-label">Дата <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', isset($event) ? $event->start_date : '') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="start_time" class="form-label">Время <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time', isset($event) ? $event->start_time : '') }}" required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="duration" class="form-label">Продолжительность (мин.) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration', $event->duration ?? 120) }}" required>
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="price" class="form-label">Стоимость (сом)</label>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $event->price ?? 0) }}">
                                    <div class="form-text">Укажите 0 для бесплатного мероприятия</div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Описание <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="10" required>{{ old('description', $event->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Изображение</label>
                            <div class="mb-3">
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                <div class="form-text">Загрузите файл изображения (рекомендуемый размер: 1280x720px)</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <input type="url" class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" placeholder="или укажите URL изображения" value="{{ old('image_url', (isset($event) && strpos($event->image_path, 'http') === 0) ? $event->image_path : '') }}">
                                <div class="form-text">Или укажите URL изображения из Интернета</div>
                                @error('image_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            @if(isset($event) && $event->image_path)
                                <div class="mt-3">
                                    <p class="fw-bold">Текущее изображение:</p>
                                    <div class="card">
                                        <div class="card-body">
                                            @if(strpos($event->image_path, 'http') === 0)
                                                <img src="{{ $event->image_path }}" alt="{{ $event->title }}" class="img-fluid rounded">
                                                <p class="mt-2 mb-0"><small class="text-muted">Изображение по URL: {{ $event->image_path }}</small></p>
                                            @else
                                                <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="img-fluid rounded">
                                                <p class="mt-2 mb-0"><small class="text-muted">Загруженное изображение: {{ $event->image_path }}</small></p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.events') }}" class="btn btn-outline-secondary">Отмена</a>
                    <button type="submit" class="btn btn-primary">
                        {{ isset($event) ? 'Сохранить изменения' : 'Создать мероприятие' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection 