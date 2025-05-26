@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Очистка базы данных</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Вернуться на панель
            </a>
        </div>
    </div>

    <div class="alert alert-danger mb-4">
        <h4 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Внимание!</h4>
        <p>Эта страница позволяет удалить данные из базы данных. Это действие <strong>необратимо</strong> и должно использоваться только для целей разработки и тестирования.</p>
        <hr>
        <p class="mb-0">Перед удалением убедитесь, что у вас есть резервная копия важных данных.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row row-cols-1 row-cols-md-2 g-4 mb-4">
        <div class="col">
            <div class="card h-100 border-danger">
                <div class="card-header bg-danger text-white">
                    <i class="bi bi-calendar-x me-2"></i>Удалить все мероприятия
                </div>
                <div class="card-body">
                    <p>Это действие удалит все мероприятия из базы данных, а также связанные с ними данные:</p>
                    <ul>
                        <li>Все комментарии к мероприятиям</li>
                        <li>Все отметки "избранное"</li>
                        <li>Все загруженные изображения</li>
                    </ul>
                    <form method="POST" action="{{ route('admin.clear-table') }}">
                        @csrf
                        <input type="hidden" name="table" value="events">
                        <div class="mb-3">
                            <label for="confirm-events" class="form-label">Введите DELETE для подтверждения</label>
                            <input type="text" class="form-control" id="confirm-events" name="confirm" required>
                        </div>
                        <button type="submit" class="btn btn-danger">Удалить все мероприятия</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 border-warning">
                <div class="card-header bg-warning text-dark">
                    <i class="bi bi-chat-x me-2"></i>Удалить все комментарии
                </div>
                <div class="card-body">
                    <p>Это действие удалит все комментарии ко всем мероприятиям, оставив сами мероприятия и отметки "избранное" нетронутыми.</p>
                    <form method="POST" action="{{ route('admin.clear-table') }}">
                        @csrf
                        <input type="hidden" name="table" value="comments">
                        <div class="mb-3">
                            <label for="confirm-comments" class="form-label">Введите DELETE для подтверждения</label>
                            <input type="text" class="form-control" id="confirm-comments" name="confirm" required>
                        </div>
                        <button type="submit" class="btn btn-warning">Удалить все комментарии</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 border-warning">
                <div class="card-header bg-warning text-dark">
                    <i class="bi bi-star-x me-2"></i>Удалить все избранное
                </div>
                <div class="card-body">
                    <p>Это действие удалит все отметки "избранное" у всех пользователей, оставив сами мероприятия и комментарии нетронутыми.</p>
                    <form method="POST" action="{{ route('admin.clear-table') }}">
                        @csrf
                        <input type="hidden" name="table" value="favorites">
                        <div class="mb-3">
                            <label for="confirm-favorites" class="form-label">Введите DELETE для подтверждения</label>
                            <input type="text" class="form-control" id="confirm-favorites" name="confirm" required>
                        </div>
                        <button type="submit" class="btn btn-warning">Удалить все избранное</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 border-danger">
                <div class="card-header bg-danger text-white">
                    <i class="bi bi-database-x me-2"></i>Удалить все данные
                </div>
                <div class="card-body">
                    <p>Это действие удалит <strong>все</strong> данные из базы (кроме пользователей):</p>
                    <ul>
                        <li>Все мероприятия</li>
                        <li>Все комментарии</li>
                        <li>Все отметки "избранное"</li>
                        <li>Все загруженные изображения</li>
                    </ul>
                    <form method="POST" action="{{ route('admin.clear-table') }}">
                        @csrf
                        <input type="hidden" name="table" value="all">
                        <div class="mb-3">
                            <label for="confirm-all" class="form-label">Введите DELETE для подтверждения</label>
                            <input type="text" class="form-control" id="confirm-all" name="confirm" required>
                        </div>
                        <button type="submit" class="btn btn-danger">Удалить все данные</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 