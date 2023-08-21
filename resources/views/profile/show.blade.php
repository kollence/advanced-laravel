<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl text-white">
                    {{$profileUser->name}}
                </div>
            </div>
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-full text-white">
                @foreach($threads as $thread)
                        <hr>
                        <hr>
                        <div class="flex  justify-between">
                        <h3 style="font-size: 20px; color: orange;" class="self-start">
                            <a href="{{$thread->path()}}">{{ $thread->title }}</a>
                        </h3>
                        <strong class="self-end">
                            <a href="{{$thread->path()}}" class="ml-2 first-of-type:ml-6 text-orange-400">
                                replies: {{$thread->replies_count}} {{Str::plural('time', $thread->replies_count)}}
                            </a>
                        </strong>
                        </div>
                        <div class="body bg-gray-500 rounded-md p-3" >{{ $thread->body }}</div>
                        <div>
                            <small>Created by: <a class="text-orange-400" href="{{route('profile.show', $thread->user->name)}}">{{ $thread->user->name }}</a></small> <br>        
                        </div>
                        <br>

                    @endforeach

                </div>
                {{$threads->links()}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
