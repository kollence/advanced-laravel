<section>

    <div class="flex">
        <div class="w-1/2">
            @can('update', $user)
            <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Profile Avatar') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __("Update your account's profile avatar image.") }}
                </p>
            </header>

            <form id="avatar_image" method="post" action="{{ route('users.avatar.store', $user) }}"  enctype="multipart/form-data"  class="mt-6 space-y-6">
                @csrf
                <div>
                    <x-input-label for="name" :value="__('Image')" />
                    <x-text-input id="avatar_img" name="avatar_img" type="file" class="mt-1 block w-full" :value="old('avatar_img', $user->avatar_img)" required autofocus autocomplete="avatar_img" />
                    <x-input-error class="mt-2" :messages="$errors->get('avatar_img')" />
                </div>
                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>

                    @if (session('status') === 'avatar-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
                    @endif
                </div>

            </form>
            @else
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('You are not authorized!') }}
            </h2>
                
            @endcan
        </div>

        <div class="w-1/2">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Profile Avatar Image') }}
            </h2>
            <div class="flex justify-center items-center">
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img class="h-1/3 w-1/3 rounded-full"
                                src="{{ asset('storage/'.$user->avatar_img) }}"
                                alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>