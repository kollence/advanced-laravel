<x-guest-layout>

    <div class="max-w-7xl mx-auto grid grid-cols-3 gap-4">

    <div class="px-5 py-12 col-span-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-300 flex justify-between">
                    <a href="{{url('threads/create')}}" class="flex bg-transparent hover:bg-blue-600 border border-blue-700 border-2 font-bold py-2 px-4 rounded-full shadow-md">+ Thread</a>
                    @auth
                    <form method="POST" action="{{ url($thread->path()) }}">
                        @csrf
                        @method('DELETE')
                        <button 
                        type="submit" 
                        class="flex bg-transparent hover:bg-red-600 border border-red-700 border-2 font-bold py-2 px-4 rounded-full shadow-md" 
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Delete') }}
                        </button>
                    </form>
                    @endauth
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <hr>
                    <hr>
                    <h1 style="font-size: 20px;">{{ $thread->title }} </h1>
                    <div class="body">{{ $thread->body }}</div>
                    <div>
                        <small>Created by: <a class="text-orange-400" href="{{route('profile.show', $thread->user->name)}}">{{ $thread->user->name }}</a></small>
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