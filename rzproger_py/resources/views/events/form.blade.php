<x-app-layout>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title mb-0">{{ isset($event) ? 'Редактировать мероприятие' : 'Создать мероприятие' }}</h1>
                    </div>
                    <div class="card-body">
                        <form method="POST" 
                            action="{{ isset($event) ? route('events.update', $event) : route('events.store') }}" 
                            enctype="multipart/form-data">
                            @csrf
                            @if (isset($event))
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label for="title" class="form-label">Название <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                    id="title" name="title" value="{{ old('title', $event->title ?? '') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Описание <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="description" name="description" rows="4" required>{{ old('description', $event->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
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
                                <div class="col-md-6 mb-3">
                                    <label for="location" class="form-label">Место проведения <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                        id="location" name="location" value="{{ old('location', $event->location ?? '') }}" required>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label">Дата <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                        id="start_date" name="start_date" 
                                        value="{{ old('start_date', isset($event) ? (isset($event->start_date) ? $event->start_date : $event->start_datetime->format('Y-m-d')) : '') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="start_time" class="form-label">Время <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                        id="start_time" name="start_time" 
                                        value="{{ old('start_time', isset($event) ? (isset($event->start_time) ? $event->start_time : $event->start_datetime->format('H:i')) : '') }}" required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="duration" class="form-label">Продолжительность (мин.) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                        id="duration" name="duration" value="{{ old('duration', $event->duration ?? '') }}" required>
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Стоимость (сом)</label>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                        id="price" name="price" value="{{ old('price', $event->price ?? '0') }}">
                                    <div class="form-text">Укажите 0 для бесплатного мероприятия</div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Изображение</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                    id="image" name="image" accept="image/*">
                                <div class="form-text">Рекомендуемый размер: 1280x720px</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                @if (isset($event) && $event->image_path)
                                    <div class="mt-2">
                                        <p>Текущее изображение:</p>
                                        @if (strpos($event->image_path, 'http') === 0)
                                            <img src="{{ $event->image_path }}" alt="{{ $event->title }}" class="img-thumbnail" style="max-height: 200px;">
                                        @else
                                            <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="img-thumbnail" style="max-height: 200px;">
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($event) ? 'Обновить' : 'Создать' }}
                                </button>
                                <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">Отмена</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 