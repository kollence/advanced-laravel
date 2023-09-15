<x-guest-layout>
    <!-- @include('threads.filters') -->
    <div class="max-w-7x1 mx-auto">
        <div class="flex px-8">
            <div class="w-2/3">
                <div class="px-1 py-12">
                    <div class="mx-auto sm:px-3 lg:px-4">
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
                                            <strong style="font-size: 20px; color: #ed7400;">
                                                {{ $thread->title }}
                                            </strong>
                                            @else
                                            <span style="font-size: 20px; color: orange;">
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
                                <div class="body bg-gray-500 rounded-md p-3">{{ $thread->body }}</div>
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
            <div class="w-1/3">
                <div class="px-1 py-12">
                    <div class="mx-auto sm:px-1 lg:px-2">
                        <div class="px-6 pt-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                            <h3 class="font-bold text-2xl text-gray-100">Top 5 Trending Thread</h3>
                        </div>
                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 text-gray-100">
                                        @foreach($trendingThreads as $trend)
                                        <div class="py-2">
                                            <a href="{{$trend->path}}" class="font-bold text-orange-400">{{$trend->title}}</a>
                                            <hr>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-guest-layout>