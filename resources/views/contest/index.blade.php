<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Welcome to your contents!') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white flex justify-center dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            @auth()
            <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{route('contest.emails.store')}}" method="post" class="grid">
                        <input type="hidden" name="contest_email" value="{{auth()->user()->email}}">
                        <button class="bg-lime-500 border-0 rounded-full px-8 py-3 text-xl text-stone-900">START YOUR CONTEST NOW BY PRESSING THIS BUTTON!</button>
                        <p class="justify-self-center">with email: {{auth()->user()->email}}</p>
                    </form>
            </div>
            @else
            <div class="p-6 text-gray-900 dark:text-gray-100 text-xl">
                    {{ __("Before we start! You'll need to login!") }}
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100 text-xl">
                    {{ __("If you are not still authenticated then please:") }}
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100 text-xl">
                    <a class="strong-links" href="{{url('login')}}">Login</a>
                    <div>Or</div>
                    <a class="weak-links" href="{{url('register')}}">Register</a>
                </div>

            @endauth
            </div>
        </div>
    </div>
</x-app-layout>
