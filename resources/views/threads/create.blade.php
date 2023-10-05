<style>
    .error {
        color: red;
    }
</style>
<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Forum Threads') }}
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto">
        <form method="POST" action="{{ route('threads.store') }}">
            @csrf
            @if ($errors->has('channel_id'))
                <span class="error">{{ $errors->first('channel_id') }}</span>
            @endif
            <select name="channel_id" id="channels" class="bg-gray-500 my-3 w-full" required>
                <option value="">Select a channel</option>
                @foreach ($channels as $channel)
                <option value="{{ $channel->id }}" {{old('channel_id') == $channel->id ? 'selected' : ''}}>{{ $channel->name }}</option>
                @endforeach
            </select>

            <!-- Email Address -->
            <div>
                @if ($errors->has('title'))
                    <span class="error">{{ $errors->first('title') }}</span>
                @endif
                <label for="title">
                    <input value="{{old('title')}}" type="text" name="title" id="title" class="bg-gray-500 w-full" required>
                </label>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
    
            <!-- Password -->
            <div class="mt-4">
                @if ($errors->has('body'))
                    <span class="error">{{ $errors->first('body') }}</span>
                @endif
                <label for="body">
                    <textarea name="body" id="body" cols="30" rows="10" class="bg-gray-500 w-full" required>{{old('body')}}</textarea>
                </label>
    
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
    
            <div class="mt-4">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded-full" type="submit">Submit</button>
                <span class="ml-7">
                    <a href="{{ url('threads') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-full">Cancel</a>
                </span>
            </div>
    
        </form>

    </div>
</x-guest-layout>