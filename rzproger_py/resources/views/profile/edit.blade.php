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
                        <h1 class="card-title mb-0">Профиль</h1>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label for="name" class="form-label">Имя</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                    id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </form>

                        <hr class="my-4">

                        <h3>Смена пароля</h3>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Текущий пароль</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                    id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Новый пароль</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                    id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                                <input type="password" class="form-control" 
                                    id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Изменить пароль</button>
                        </form>

                        <hr class="my-4">

                        <h3>Удаление аккаунта</h3>
                        <p class="text-muted">После удаления аккаунта все ваши данные будут безвозвратно удалены.</p>
                        
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            Удалить аккаунт
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно для подтверждения удаления аккаунта -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Подтверждение удаления</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Вы уверены, что хотите удалить свой аккаунт? Это действие нельзя отменить.</p>
                    <form method="POST" action="{{ route('profile.destroy') }}" id="delete-account-form">
                        @csrf
                        @method('DELETE')
                        
                        <div class="mb-3">
                            <label for="delete-password" class="form-label">Введите пароль для подтверждения</label>
                            <input type="password" class="form-control" id="delete-password" name="password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" onclick="document.getElementById('delete-account-form').submit();">
                        Удалить аккаунт
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 