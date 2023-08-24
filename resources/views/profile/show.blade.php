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
                    @foreach($activities as $date => $activity)
                        <h4>{{$date}}</h4>
                        @foreach($activity as $record)
                                <!-- check if blade view exists -->
                            @if(view()->exists("profile.activities.{$record->type}"))
                                <!-- polimorph in blade || event.type -->
                                @include("profile.activities.{$record->type}", ['activity'=> $record])
                            @endif
                        @endforeach
                    @endforeach

                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>