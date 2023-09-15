<x-guest-layout>

    <div class="max-w-7xl mx-auto grid grid-cols-3 gap-4">

        <div class="px-5 py-12 col-span-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-300 flex justify-between">
                        <a href="{{url('threads/create')}}" class="bg-transparent hover:bg-blue-600 border border-blue-700 border-2 font-bold py-2 px-4 rounded-full shadow-md">+ Thread</a>
                        @can('update', $thread)
                        <form method="POST" action="{{ url($thread->path()) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex bg-transparent hover:bg-red-600 border border-red-700 border-2 font-bold py-2 px-4 rounded-full shadow-md" onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                {{ __('Delete') }}
                            </button>
                        </form>
                        @endcan
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
                        <div id="reply-holder" class="p-6 text-orange-500 dark:text-gray-100 bg-slate-600">
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
                <div class="flex items-center p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 mr-3">
                                <img class="h-10 w-10 rounded-full" src="{{ asset($thread->user->avatar()) }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="basis-1/1">Created by: {{$thread->user->name}}</div>
                </div>
                <div class="basis-1/1">Created: {{$thread->created_at->diffForHumans() }}</div>
                <div class="basis-1/1" id="comments_number">Comments: {{$thread->replies_count}}</div>

                <br>
                <button type="button" id="subscribe-btn" onclick='subscribe("{{$thread->id}}", this)' data-subscribed="{{$thread->is_subscribed_to ? '1' : '0'}}" class="bg-transparent {{$thread->is_subscribed_to ? 'hover:bg-red-600 border-red-700' : 'hover:bg-blue-600 border-blue-700'}} border border-2 font-bold py-2 px-4 rounded-full shadow-md">
                    {{$thread->is_subscribed_to ? 'Subscribed' : 'Subscribe'}}
                </button>
            </div>
        </div>
    </div>
    <script>
        function subscribe(replyId) {
            const subscribeRoute = "{{$thread->path()}}" + '/subscriptions';
            // const replyDiv = document.getElementById(`reply-${replyId}`);
            const countSubscribed = document.getElementById(`subscribe-btn`);

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            if (countSubscribed.getAttribute('data-subscribed') == 1) {
                formData.append('_method', 'DELETE');
            }

            fetch(subscribeRoute, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(response => {
                    if (response.isSubscribed !== undefined) {
                        if (response.isSubscribed) {
                            countSubscribed.innerHTML = 'Subscribed';
                            countSubscribed.setAttribute('data-subscribed', 1);
                            countSubscribed.classList.add("hover:bg-red-600", "border-red-700");
                            countSubscribed.classList.remove("hover:bg-blue-600", "border-blue-700");
                        } else {
                            countSubscribed.innerHTML = 'Subscribe';
                            countSubscribed.setAttribute('data-subscribed', 0);
                            countSubscribed.classList.add("hover:bg-blue-600", "border-blue-700");
                            countSubscribed.classList.remove("hover:bg-red-600", "border-red-700");
                        }
                    } else {
                        console.error('Error marking thread as subscribed:', response);
                    }
                })
                .catch(error => {
                    console.error('Error marking thread as subscribed:', error);
                });
        }
    </script>
</x-guest-layout>