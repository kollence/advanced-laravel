<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex items-center p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-3">
                            <img class="h-10 w-10 rounded-full"
                                src="{{ asset($profileUser->avatar()) }}"
                                alt="">
                        </div>
                    </div>
                </div>
                <div class="max-w-xl text-white">
                    {{$profileUser->name}}
                </div>
            </div>
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-full text-white">
                    @forelse($activities as $date => $activity)
                        <h4>{{$date}}</h4>
                        @foreach($activity as $record)
                                <!-- check if blade view exists -->
                            @if(view()->exists("profile.activities.{$record->type}"))
                                <!-- polimorph in blade || event.type -->
                                @include("profile.activities.{$record->type}", ['activity'=> $record])
                            @endif
                        @endforeach
                    @empty
                     <p>There is no activity yet</p>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>