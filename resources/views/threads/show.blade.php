<x-guest-layout>
@include('threads.filters')
    <div class="max-w-7xl mx-auto grid grid-cols-3 gap-4">

    <div class="px-5 py-12 col-span-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-300">
                    <a href="{{url('threads/create')}}">+ Thread</a>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <hr>
                    <hr>
                    <h1 style="font-size: 20px;">{{ $thread->title }} </h1>
                    <div class="body">{{ $thread->body }}</div>
                    <div>
                        <small>Created by: <a class="text-orange-400" href="{{route('profile.show', $thread->user->id)}}">{{ $thread->user->name }}</a></small>
                    </div>
                    <br>
                </div>
                <div class="text-gray-900 dark:text-gray-100">
                    @include('threads.reply-form')
                </div>
                <div class="p-6 text-orange-500 dark:text-gray-100 bg-slate-600">
                    <h4 style="font-size: 20px;">Replies</h4>
                    @forelse($replies as $reply)
                    @include('threads.reply')
                    @empty
                    <p>No comments</p>
                    @endforelse
                </div>
                {{$replies->links()}}
            </div>
            </div>
        </div>
    </div>
    <div class="col-span-1 rounded-lg" style="border: thin solid gray;">
        <div class="flex flex-col p-4 text-gray-400">
            <div class="basis-1/1">Created: {{$thread->created_at->diffForHumans() }}</div>
            <div class="basis-1/1">Comments: {{$thread->replies_count}}</div>
            <div class="basis-1/1">Created by: {{$thread->user->name}}</div>
        </div>  
    </div>
    </div>
</x-guest-layout>