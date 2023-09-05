<x-guest-layout> 
<!-- @include('threads.filters') -->
<div class="max-w-7xl mx-auto grid grid-cols-1 gap-4">

    <div class="px-5 py-12 col-span-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-300">
                    <a href="{{url('threads/create')}}" class="bg-transparent hover:bg-blue-600 border border-blue-700 border-2 font-bold py-2 px-4 rounded-full shadow-md">+ Thread</a>
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @forelse($threads as $thread)
                        <hr>
                        <hr>
                        <div class="flex  justify-between">
                        <h3 class="self-start">
                            <a href="{{$thread->path()}}">
                                @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                    <strong  style="font-size: 20px; color: #ed7400;">
                                        {{ $thread->title }}
                                    </strong>
                                @else
                                    <span  style="font-size: 20px; color: orange;">
                                        {{ $thread->title }} 
                                    </span>
                                @endif
                            </a>
                        </h3>
                        <strong class="self-end">
                            <a href="{{$thread->path()}}" class="ml-2 first-of-type:ml-6 text-orange-400">
                                {{$thread->replies_count}} {{Str::plural('reply', $thread->replies_count)}}
                            </a>
                        </strong>
                        </div>
                        <div class="body bg-gray-500 rounded-md p-3" >{{ $thread->body }}</div>
                        <div>
                            <small>Created by: <a class="text-orange-400" href="{{route('profile.show', $thread->user->name)}}">{{ $thread->user->name }}</a></small> <br>        
                        </div>
                        <br>
                    @empty
                        <h1 class="text-center">No threads found</h1>
                    @endforelse

                </div>
                {{$threads->links()}}
            </div>
        </div>
    </div>

    </div>
</x-guest-layout>
