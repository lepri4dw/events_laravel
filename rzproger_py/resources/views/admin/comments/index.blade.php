@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Управление комментариями</h1>
    </div>
    
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col" width="60">#</th>
                            <th scope="col">Пользователь</th>
                            <th scope="col">Мероприятие</th>
                            <th scope="col">Комментарий</th>
                            <th scope="col">Дата</th>
                            <th scope="col" width="80">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comments as $comment)
                            <tr>
                                <td>{{ $comment->id }}</td>
                                <td>
                                    @if($comment->user)
                                        {{ $comment->user->name }}
                                        <br>
                                        <small class="text-muted">{{ $comment->user->email }}</small>
                                    @else
                                        <span class="text-muted">Пользователь удален</span>
                                    @endif
                                </td>
                                <td>
                                    @if($comment->event)
                                        <a href="{{ route('events.show', $comment->event) }}" target="_blank">
                                            {{ Str::limit($comment->event->title, 30) }}
                                        </a>
                                    @else
                                        <span class="text-muted">Мероприятие удалено</span>
                                    @endif
                                </td>
                                <td>{{ $comment->content }}</td>
                                <td>{{ $comment->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот комментарий?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="mb-0">Комментарии не найдены</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $comments->links() }}
    </div>
@endsection 