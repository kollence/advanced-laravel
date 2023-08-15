<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Forum Threads') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-300">
                    <a href="{{url('threads/create')}}">+ Thread</a>
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @foreach($threads as $thread)
                        <hr>
                        <hr>
                        <h3 style="font-size: 20px; color: orange;">
                            <a href="{{$thread->path()}}">{{ $thread->title }}</a>
                        </h3>
                            <div class="body bg-gray-500 rounded-md p-3" >{{ $thread->body }}</div>
                            <div>
                                <small>Created by: {{$thread->user->name}}</small>
                            </div>
                        <br>

                    @endforeach

                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
