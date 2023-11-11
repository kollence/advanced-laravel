<x-guest-layout>
    <!-- @include('threads.filters') -->
    <div class="max-w-7x1 mx-auto">
        <div class="flex px-8">
            <div class="w-1/6">
                <div class="px-1 py-12">
                    <div class="mx-auto sm:px-3 lg:px-4">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <h3 class="font-bold text-2xl text-gray-100">Search</h3>

                                <div class="my-2">
                                    <div class="flex flex-wrap  mb-3">
                                            <div class="flex">
                                                <label for="q" class="checkbox-filters">
                                                    <input class="text-black rounded-l-lg" type="search" id="q" name="q" value="{{request()->has('q') ? request()->input('q') : old('q')}}">
                                                </label>
                                                <button type="button" id="search-btn" class="bg-green-300 rounded-r-lg p-2">&#x1F50E;</button>
                                            </div>
                                    </div>
                                </div>
                                <h3 class="font-bold text-2xl mt-5 text-gray-100">Filters</h3>

                                <div class="mt-2">
                                    <div class="flex flex-wrap -mx-1">
                                        <label for="popular" class="w-full">
                                            <input type="checkbox" id="popular" class="checkbox-filters" name="popular" value="1" {{ request()->has('popular') ? 'checked' : '' }}>
                                            popular
                                        </label>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="flex flex-wrap -mx-1">
                                        <label for="unanswered" class="w-full">
                                            <input type="checkbox" id="unanswered" class="checkbox-filters" name="unanswered" value="1" {{ request()->has('unanswered') ? 'checked' : '' }}>
                                            unanswered
                                        </label>
                                    </div>
                                </div>
                                @auth
                                <div class="my-2">
                                    <div class="flex flex-wrap -mx-1">
                                        <label for="by" class="w-full">
                                            <input type="checkbox" id="by" class="checkbox-filters" name="by" value="{{auth()->user()->name}}" {{ request()->has('by') ? 'checked' : '' }}>
                                            my threads
                                        </label>
                                    </div>
                                </div>
                                @endauth

                                <h3 class="font-bold text-2xl mt-5 text-gray-100">Channels</h3>

                                @forelse($channels as $channel)
                                <div class="mt-2">
                                    <div class="flex flex-wrap -mx-1">
                                        <label for="{{$channel->slug}}" class="w-full">
                                            <input type="checkbox" class="checkbox-categories" id="{{$channel->slug}}" name="channel_id[]" value="{{$channel->id}}" {{ in_array($channel->id, request()->input('channel_id', [])) ? 'checked' : '' }}>
                                            {{$channel->name}}
                                        </label>
                                    </div>
                                </div>
                                @empty
                                Channels are not added yet
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-3/6">
                <div class="px-1 py-12">
                    <div class="mx-auto sm:px-3 lg:px-4">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                @forelse($threads as $thread)
                                <div class="flex  justify-between">
                                    <h3 class="self-start">
                                        <a href="{{$thread->path()}}">
                                            @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                            <strong class="strong-links">
                                                {{ $thread->title }}
                                            </strong>
                                            @else
                                            <span class="weak-links">
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
                                <div class="mt-1 flex justify-between">
                                    <small>Created by: <a class="text-orange-400" href="{{route('profile.show', $thread->user->name)}}">{{ $thread->user->name }}</a>
                                      <span>&nbsp;channel: </span><span class="text-orange-400">{{$thread->channel->name}}</span>
                                    </small>
                                    <small>Visited: <b class="text-orange-400">{{ $thread->visits()->count() }}</b></small>
                                </div>
                                <hr>
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
            <div class="w-2/6">
                <div class="px-1 py-12">
                    <div class="mx-auto sm:px-1 lg:px-2">
                        <div class="px-6 pt-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                            <h3 class="font-bold text-2xl text-gray-100">Top 5 Trending Thread</h3>

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

            <script>
                $(document).ready(function() {
                    const checkboxes = $('input[type="checkbox"]');
                    const currentUrl = new URL(window.location.href);
                    const queryString = currentUrl.searchParams;
                    
                    checkboxes.on('change', function() {
                        const checkedCheckboxes = checkboxes.filter(':checked');
                        const channelIds = checkedCheckboxes.map(function() {
                            return $(this).attr('name') + '=' + $(this).val();
                        }).get();
                        const query = channelIds.length > 0 ? channelIds.join('&') : '';
                        let newUrlz = currentUrl.href.split('?')[0] + (query ? '?' + query : '');
                        // Append the "q" parameter to the existing query string
                        if ($('#q').val()) {
                            let sign = (query) ? '&' : '?';
                            newUrlz += sign +'q='+$('#q').val();
                        } 
                        newUrl(newUrlz);
                    });

                    $('#search-btn').on('click', function() {
                        const query = $('#q').val();
                        if (query) {
                            queryString.set('q', $('#q').val());
                        } else {
                            queryString.delete('q');
                        }

                        newUrl(currentUrl.toString());
                    });

                    function newUrl(url) {
                        window.location.href = url;
                    }
                });
            </script>
        </div>
</x-guest-layout>