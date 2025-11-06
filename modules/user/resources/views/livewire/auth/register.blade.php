<div>
    <form wire:submit.prevent="register">

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Choose Role -->

        <div class="mt-4">
            <x-input-label for="role" :value="__('Choose Your Role')" />

            <div class="flex items-center justify-between  mt-2" dir="rtl">
                <label for="mentee_role" class="flex items-center cursor-pointer p-3 rounded-lg border border-gray-300 transition duration-150 ease-in-out hover:bg-gray-50">
                    <input type="radio" id="mentee_role" wire:model="role" value="mentee" class="text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm font-medium text-gray-700 mr-2"> (Mentee)</span>
                </label>

                <label for="mentor_role" class="flex items-center cursor-pointer p-3 rounded-lg border border-gray-300 transition duration-150 ease-in-out hover:bg-gray-50">
                    <input type="radio" id="mentor_role" wire:model="role" value="mentor" class="text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm font-medium text-gray-700 mr-2"> (Mentor)</span>
                </label>
            </div>

            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
