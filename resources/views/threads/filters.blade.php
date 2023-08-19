<div class="space-x-8 sm:-my-px sm:ml-10 sm:flex pt-4">
    <x-dropdown align="right" width="48" class="">
        <x-slot name="trigger">
            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                <div>Channels</div>

                <div class="ml-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </x-slot>

        <x-slot name="content">  
            @foreach($channels as $channel)                     
            <x-dropdown-link :href="route('threads.index', $channel->slug)">
                {{$channel->slug}}
            </x-dropdown-link>
            @endforeach
        </x-slot>
    </x-dropdown>
    <x-dropdown align="right" width="48" class="">
        <x-slot name="trigger">
            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                <div>Browse</div>

                <div class="ml-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </x-slot>

        <x-slot name="content">                    
            <x-dropdown-link :href="route('threads.index')">
                All threads
            </x-dropdown-link>
            @auth
            <x-dropdown-link :href="route('threads.index', ['by' => auth()->user()->name])">
                My threads
            </x-dropdown-link>
            @endauth
            <x-dropdown-link :href="route('threads.index', ['popular' => 1])">
                Popular
            </x-dropdown-link>
        </x-slot>
    </x-dropdown>
</div> 