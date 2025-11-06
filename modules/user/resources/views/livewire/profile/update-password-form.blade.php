

<section class="relative w-full">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>
    @if (session()->has('password_success'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 4000)"
            class="p-4 absolute right-0 top-0 mb-8 rounded-lg bg-green-100 border border-green-400 text-green-700 font-medium"
            role="alert">
            {{ session('password_success') }}
        </div>
    @endif
    @if (session()->has('password_error'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 4000)"
            class="p-4 absolute right-0 top-0 mb-8 rounded-lg bg-green-100 border border-green-400 text-green-700 font-medium"
            role="alert">
            {{ session('password_error') }}
        </div>
    @endif
    <form wire:submit="updatePassword" class="mt-6 space-y-6">
        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input wire:model="current_password" id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input wire:model="password" id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-text-input wire:model="password_confirmation" id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-success-button>{{ __('Save') }}</x-success-button>

{{--            <x-action-message class="me-3" on="password-updated">--}}
{{--                {{ __('Saved.') }}--}}
{{--            </x-action-message>--}}
        </div>
    </form>
</section>
