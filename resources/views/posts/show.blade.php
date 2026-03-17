@extends('layouts.app')

@section('title', $post->title)

@section('header')
    <h2>
        {{ $post->title }}
    </h2>
@endsection

@section('content')
    <article>
        <div>
            <p>
                Автор: {{ $post->author->name }}
                Дата {{ $post->created_at->format('F d, Y') }}
                @if($post->updated_at->ne($post->created_at))
                    Обновлено {{ $post->updated_at->format('F d, Y') }}
                @endif
            </p>
        </div>
        <div>{{ $post->body }}</div>
    </article>

    <div>
        @auth
            @if(auth()->id() === $post->user_id || in_array(auth()->user()->role, ['editor', 'admin']))
                <a href="{{ route('posts.edit', $post) }}">Бредактировать </a>

                <form method="POST" action="{{ route('posts.destroy', $post) }}"
                        onsubmit="return confirm('Точно удалить удалить пост?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Удалить</button>
                </form>
            @endif
        @endauth
    </div>

    <div >
        <h3>Комментарии ({{ $post->comments->count() }})</h3>

        @auth
            <form method="POST" action="{{ route('comments.store', $post) }}">
                @csrf
                <textarea name="body" rows="3"
                          @error('body')  @enderror
                          placeholder="Напишите комментарий...">{{ old('body') }}</textarea>
                @error('body')
                    <p>{{ $message }}</p>
                @enderror
                <button type="submit">
                    Отправить
                </button>
            </form>
        @else
            <p>
                <a href="{{ route('login') }}">Войдите чтобы оставить комментарий.</a>,
            </p>
        @endauth

        <div>
            @forelse($post->comments as $comment)
                <div>
                    <span>{{ $comment->author->name }}</span>
                    <span>{{ $comment->created_at->diffForHumans() }}</span>
                    <p>{{ $comment->body }}</p>

                    @auth
                        @if(auth()->id() === $comment->user_id || auth()->user()->role === 'admin')
                            <form method="POST" action="{{ route('comments.destroy', $comment) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit">
                                    Удалить
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            @empty
                <p>Пока нет комментариев. Будьте первым!</p>
            @endforelse
        </div>
    </div>

    <div>
        <a href="{{ route('posts.index') }}"> ← Назад </a>
    </div>
@endsection
