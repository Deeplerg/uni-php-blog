<a href="{{ route('posts.create') }}">
    Create New Post
</a>

<div class="flex flex-col flex-wrap">
    @foreach ($posts as $post)
        <div class="flex justify-between items-start">
            <div>
                <h3>{{ $post->title }}</h3>
                <div>
                    <a href="{{ route('posts.edit', $post) }}"
                       title="Edit Post">
                        <x-heroicon-o-pencil width="20" height="20" />
                    </a>

                    <form method="POST"
                          action="{{ route('posts.destroy', $post) }}"
                          onsubmit="return confirm('Are you sure you want to delete post {{$post->title}}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Delete Post">
                            <x-heroicon-o-trash width="20" height="20" />
                        </button>
                    </form>
                </div>
                <p>
                    Written by {{ $post->author->name }}
                    on {{ $post->created_at->format('M d, Y') }}
                </p>
            </div>

            <div>{{ $post->body }}</div>
        </div>
    @endforeach
</div>
