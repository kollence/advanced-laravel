@if(auth()->check())
<div class="border">
    <form action="{{ route('threads-reply.store', $thread) }}" method="POST">
        @csrf
        <div class="form-group">
            <textarea name="body" id="body" class="form-control w-full" placeholder="Have something to say?" rows="2"></textarea>
        </div>
        <button type="submit" class="btn btn-primary border p-2">Post</button>
    </form>
</div>
@else
    <p class="text-center">Please <a class="text-orange-200" href="{{ route('login') }}">sign in</a> to participate in this discussion.</p>
@endif