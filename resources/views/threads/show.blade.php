<x-guest-layout>

    <div class="max-w-7xl mx-auto grid grid-cols-3 gap-4">

        <div class="px-5 py-12 col-span-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-300 flex justify-between">
                        @can('update', $thread)
                        <a href="{{route('threads.edit', $thread->slug)}}" class="bg-transparent hover:text-gray-900 hover:bg-orange-400 border border-orange-500 border-2 font-bold py-2 px-4 rounded-full shadow-md">+ Edit</a>
                        
                        <form method="POST" action="{{ url($thread->path()) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex bg-transparent hover:text-gray-900 hover:bg-red-600 border border-red-700 border-2 font-bold py-2 px-4 rounded-full shadow-md" onclick="event.preventDefault();
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
                        
                        <div class="text-gray-900 dark:text-gray-100 flex justify-center" id="reply-section">
                            <div id="dynamic-section" class="grow grid justify-items-stretch">
                            @if(!$thread->locked)
                                @include('threads.reply-form')
                            @else
                                <h1 class="text-red-300 ml-5 text-xl">Thread has been locked. No more replies</h1>
                            @endif
                            </div>
                        </div>
                        
                        <div id="replies-holder" class="p-6 text-orange-500 dark:text-gray-100 bg-slate-800">
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
                @auth
                <button type="button" id="subscribe-btn" onclick='subscribe("{{$thread->id}}")' data-subscribed="{{$thread->is_subscribed_to ? '1' : '0'}}" class="bg-transparent {{$thread->is_subscribed_to ? 'hover:bg-red-600 border-red-700' : 'hover:bg-blue-400 border-blue-700 hover:text-black'}} border border-2 font-bold py-2 px-4 rounded-full shadow-md">
                    {{$thread->is_subscribed_to ? 'Subscribed' : 'Subscribe'}}
                </button>
                
                <br>
                @can('update', $thread)
                    <button type="button" id="lock-btn" onclick='lock("{{$thread->id}}", this)' data-locked="{{$thread->locked ? '1' : '0'}}" class="bg-transparent {{$thread->locked ? 'hover:bg-red-600 border-red-700' : 'hover:bg-green-600 border-green-700 hover:text-black'}} border border-2 font-bold py-2 px-4 rounded-full shadow-md">
                        {{$thread->locked ? 'Locked' : 'Unlocked'}}
                    </button>
                    <br>
                    <button type="button" id="un-mark-btn" onclick='unMarkBestReply("{{$thread->best_reply_id}}", this)' {{$thread->best_reply_id == null ? 'disabled' : ''}} data-unmark-reply="{{$thread->best_reply_id}}" class="bg-transparent {{$thread->best_reply_id !== null ? 'hover:bg-orange-400 border-orange-500  hover:text-black' : 'hover:bg-gray-600 border-gray-700 hover:text-black'}} border border-2 font-bold py-2 px-4 rounded-full shadow-md">
                        {{$thread->best_reply_id ? 'Un mark the best reply' : 'There is not best reply yet'}}
                    </button>
                @endcan
                @endauth
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

        function lock(replyId, btn) {
            let dynamicSection = document.getElementById('dynamic-section');
            let Route = "";
            let dataValue = btn.getAttribute('data-locked');
            if(dataValue == '1'){
                Route = "{{$thread->path()}}" + '/unlock';
            }else if(dataValue == '0'){
                Route = "{{$thread->path()}}" + '/lock';
            }
            // console.log(Route);
            // const replyDiv = document.getElementById(`reply-${replyId}`);

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');

            fetch(Route, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(response => {
                        if (dataValue == "0") {
                            btn.innerHTML = 'Locked';
                            btn.setAttribute('data-locked', 1);
                            dynamicSection.innerHTML = `<h1 class="text-red-300 ml-5 text-xl">Thread has been locked. No more replies</h1>`
                            btn.classList.add("hover:bg-red-600", "border-red-700");
                            btn.classList.remove("hover:bg-green-600", "border-green-700", "hover:text-black");
                        }else if(dataValue == "1"){
                            btn.innerHTML = 'Unlocked';
                            btn.setAttribute('data-locked', 0);
                            dynamicSection.innerHTML = `
                                @include('threads.reply-form')
                            `;
                            btn.classList.add("hover:bg-green-600", "border-green-700", "hover:text-black");
                            btn.classList.remove("hover:bg-red-600", "border-red-700");
                        }else{
                            console.log('error: data value not found!');
                        }
                })
                .catch(error => {
                    console.error('Error marking thread as locked:', error);
                });
        }
        function unMarkBestReply(replyId, button) {
        // console.log(replyId,button);
        
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        fetch('/replies/'+replyId+'/un-mark-best', {
                method: 'POST',
                body: formData,
            }).then(response => {
                if(response.ok){
                    document.querySelectorAll('[id^="reply-"]').forEach(e => {
                        e.style.backgroundColor="transparent"
                    })
                    document.querySelectorAll('[id^="mark-best-content-"]').forEach(e => {
                        e.innerHTML = `<button type="button" id="mark-best-btn-${replyId}" onclick='markAsBestReply("`+e.getAttribute('data-id')+`", this)' class="bg-teal-600/40 hover:bg-orange-900 border border-orange-300 border-1 font-bold my-1 px-2 rounded-md shadow-md">mark as best</button>`
                    })
                    button.classList.add("hover:bg-gray-600", "border-gray-700");
                    button.classList.remove("hover:bg-orange-400", "border-orange-500", "hover:text-black");
                    button.innerHTML = 'There is not best reply yet';

                }
            }    
        ).catch(error => console.error(error))
    }
    </script>
</x-guest-layout>