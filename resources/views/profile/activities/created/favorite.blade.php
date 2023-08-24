<x-profile.activities.box-panel>
    <x-slot name="type">Favorited reply</x-slot>
    <x-slot name="title"><a href="{{$activity->subject->favorite->path()}}" class="ml-2 first-of-type:ml-6 text-orange-400">{{$activity->subject->favorite->thread->title}}</a> </x-slot>
    <x-slot name="by"><a class="text-orange-400" href="{{route('profile.show', $profileUser->name)}}">{{ $profileUser->name }}</a></x-slot>
    <x-slot name="body">{{$activity->subject->favorite->body}}</x-slot>
</x-profile.activities.box-panel>