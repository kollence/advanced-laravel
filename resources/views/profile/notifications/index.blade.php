<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-full text-white">
                    @forelse($notifications as $notification)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                
                            </div>
                            <div class="ml-4 py-2">
                                <div class="text-sm font-medium text-gray-900">
                                </div>
                                <div class="text-sm text-gray-100">
                                    <a href="{{$notification->data['link']}}" onclick="markAsRead('{{$notification->id}}')">{{$notification->data['message']}}</a>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900"></div>
                        </div>
                        
                    </div>

                    @empty
                     <p>There is no notifications yet</p>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
    <script>
        function markAsRead(notificationId) {
            const authUser = "{{auth()->user()->name}}";
            fetch(`/profile/${authUser}/notifications/${notificationId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    "_method": "DELETE"
                })
            }).then(function(response) {
                console.log('succesfully red notification');
            }).catch(error => {
                console.log(error);
            });
        }   
    </script>
    </div>
</x-app-layout>