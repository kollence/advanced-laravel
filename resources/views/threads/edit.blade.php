<style>
    .error {
        color: red;
    }
</style>
<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Forum Threads') }}
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto">
        <form method="POST" action="{{ route('threads.update', $thread) }}">
            @csrf
            @method('PATCH')
            <select name="channel_id" id="channels" class="bg-gray-500 my-3 w-full" required>
                @foreach ($channels as $channel)
                <option value="{{ $channel->id }}" {{$thread->channel_id == $channel->id ? 'selected' : ''}}>{{ $channel->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('channel_id')" class="mt-2" />

            <!-- Email Address -->
            <div>
                @if ($errors->has('title'))
                    <span class="error">{{ $errors->first('title') }}</span>
                @endif
                <label for="title">
                    <input value="{{$thread->title}}" type="text" name="title" id="title" class="bg-gray-500 w-full" required>
                </label>
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>
    
            <!-- Password -->
            <div class="mt-4">
                <label for="body">
                    <textarea name="body" id="body" cols="30" rows="10" class="bg-gray-500 w-full" required>{{$thread->body}}</textarea>
                </label>
    
                <x-input-error :messages="$errors->get('body')" class="mt-2" />
            </div>
    
            <div class="mt-4">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded-full" type="submit">Submit</button>
                <span class="ml-7">
                    <a href="{{ url($thread->path()) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-full">Cancel</a>
                </span>
            </div>
    
        </form>

    </div>
</x-guest-layout>