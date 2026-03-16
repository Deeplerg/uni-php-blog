<form method="POST" action="{{ route('posts.store') }}">
    @csrf

    <label for="title">Post Title</label>
    <input
        id="title"
        name="title"
        type="text"
        class="@error('title') is-invalid @enderror"
    />
    @error('title')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <label for="body">Post Body</label>
    <textarea
        id="body"
        name="body"
        type="text"
        rows="6"
        class="@error('body') is-invalid @enderror"
    ></textarea>
    @error('body')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <input type="submit" value="Publish Post">
</form>
