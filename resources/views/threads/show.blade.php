<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Forum Thread') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                @if($thread->replies->count())
                <div class="p-6 text-orange-500 dark:text-gray-100 bg-slate-600">
                    <h4 style="font-size: 20px;">Replies</h4>
                    @foreach($thread->replies as $reply)
                    @include('threads.reply')
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>