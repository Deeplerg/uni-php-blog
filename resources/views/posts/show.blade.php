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

        <a href="{{ route('posts.index') }}"> ← Назад </a>
@endsection
