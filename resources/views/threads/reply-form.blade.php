@auth
<div class="py-4 px-8 text-gray-900 dark:text-gray-100">
    <form action="{{ route('threads-reply.store', [$thread->channel, $thread]) }}" method="POST">
        @csrf
        <div class="form-group">
            <textarea name="body" id="body" class="rounded-md form-control w-full" style="color: black;" placeholder="Have something to say?" rows="2"></textarea>
        </div>
        <button type="submit" class="bg-slate-700 text-sm border rounded-md p-1">Reply</button>
    </form>
@if ($errors->has('body'))
    <span class="error">{{ $errors->first('body') }}</span>
@endif

</div>
@else
    <p class="text-center">Please <a class="text-orange-200" href="{{ route('login') }}">sign in</a> to participate in this discussion.</p>
@endauth