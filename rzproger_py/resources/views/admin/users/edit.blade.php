@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Управление пользователем</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Назад к списку
            </a>
        </div>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" id="userForm">
                @csrf
                @method('PUT')

                <div class="card mb-4">
                    <div class="card-header">
                        Информация о пользователе
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-3">ID:</dt>
                            <dd class="col-sm-9">{{ $user->id }}</dd>

                            <dt class="col-sm-3">Имя:</dt>
                            <dd class="col-sm-9">{{ $user->name }}</dd>

                            <dt class="col-sm-3">Email:</dt>
                            <dd class="col-sm-9">{{ $user->email }}</dd>

                            <dt class="col-sm-3">Дата регистрации:</dt>
                            <dd class="col-sm-9">{{ $user->created_at->format('d.m.Y H:i') }}</dd>

                            <dt class="col-sm-3">Последнее обновление:</dt>
                            <dd class="col-sm-9">{{ $user->updated_at->format('d.m.Y H:i') }}</dd>
                        </dl>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            Права доступа
                        </div>
                        <div class="card-body">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" {{ old('is_admin', $user->isAdmin()) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_admin">Администратор</label>
                                <div class="form-text">Пользователь с правами администратора имеет доступ к админ-панели и управлению всеми данными сайта.</div>
                            </div>

                            @if($user->id === auth()->id())
                                <div class="alert alert-warning mt-3 mb-0">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    Внимание! Вы редактируете свою учетную запись. Снятие прав администратора приведет к потере доступа к админ-панели.
                                </div>
                            @endif

                            <div id="statusMessage" class="alert mt-3" style="display: none;"></div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Отмена</a>
                    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('userForm');
    const statusMessage = document.getElementById('statusMessage');
    const adminCheckbox = document.getElementById('is_admin');
    const isCurrentUser = {{ $user->id === auth()->id() ? 'true' : 'false' }};

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Проверка на снятие прав у самого себя
        if (isCurrentUser && !adminCheckbox.checked) {
            statusMessage.className = 'alert alert-danger mt-3';
            statusMessage.style.display = 'block';
            statusMessage.textContent = 'Вы не можете снять права администратора у самого себя.';
            return;
        }

        // Отправка AJAX запроса
        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                _method: 'PUT',
                is_admin: adminCheckbox.checked
            })
        })
        .then(response => response.json())
        .then(data => {
            statusMessage.className = `alert alert-${data.success ? 'success' : 'danger'} mt-3`;
            statusMessage.style.display = 'block';
            statusMessage.textContent = data.message;

            if (data.success) {
                // Обновляем чекбокс в соответствии с новым статусом
                adminCheckbox.checked = data.is_admin;
            }
        })
        .catch(error => {
            statusMessage.className = 'alert alert-danger mt-3';
            statusMessage.style.display = 'block';
            statusMessage.textContent = 'Произошла ошибка при обновлении статуса.';
        });
    });
});
</script>
@endpush 