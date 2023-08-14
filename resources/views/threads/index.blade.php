<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Forum Threads') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @foreach($threads as $thread)
                        <hr>
                        <hr>
                        <h3 style="font-size: 20px; color: orange;">
                            <a href="{{route('threads.show', $thread->id)}}">{{ $thread->title }}</a>
                        </h3>
                            <div class="body">{{ $thread->body }}</div>
                        <br>

                    @endforeach

                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
