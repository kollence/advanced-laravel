<x-guest-layout>
<div class="space-x-8 sm:-my-px sm:ml-10 sm:flex pt-4">
    <x-dropdown align="right" width="48" class="">
        <x-slot name="trigger">
            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                <div>Channels</div>

                <div class="ml-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </x-slot>

        <x-slot name="content">  
            @foreach($channels as $channel)                     
            <x-dropdown-link :href="route('threads.index', $channel->slug)">
                {{$channel->slug}}
            </x-dropdown-link>
            @endforeach
        </x-slot>
    </x-dropdown>
    <x-dropdown align="right" width="48" class="">
        <x-slot name="trigger">
            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                <div>Browse</div>

                <div class="ml-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </x-slot>

        <x-slot name="content">                    
            <x-dropdown-link :href="route('threads.index')">
                All threads
            </x-dropdown-link>
            @auth
            <x-dropdown-link :href="route('threads.index', ['by' => auth()->user()->name])">
                My threads
            </x-dropdown-link>
            @endauth
        </x-slot>
    </x-dropdown>
</div> 
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