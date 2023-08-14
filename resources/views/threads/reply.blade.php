<div class="p-6 text-gray-900 dark:text-gray-100 ">
    <h5 style="font-size: 20px;">
        <a style=" color: orange;" href="{{route('profile.show', $reply->user->id)}}">{{ $reply->user->name }}</a>
        said...
    </h5>
    <div class="body">{{ $reply->body }}</div>
    <div class="border"> {{ $reply->created_at->diffForHumans() }} </div>
</div>