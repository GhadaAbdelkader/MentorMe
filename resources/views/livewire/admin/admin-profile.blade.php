<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Admin AdminProfile') }}
    </h2>
</x-slot>
<div class="bg-white  overflow-hidden  sm:rounded-lg relative">



        @if (session()->has('success_message'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 4000)"
                class="p-4 absolute right-0 top-0 mb-8 rounded-lg bg-green-100 border border-green-400 text-green-700 font-medium"
                role="alert">
                {{ session('success_message') }}
            </div>
        @endif
        <livewire:profile.profile-photo-manager />

    <form wire:submit.prevent="saveProfile" class="space-y-6 w-full mt-8 pb-3">
        <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden p-6 border-l-4 border border-gray-200">
            <h3 class="text-2xl font-bold text-indigo-900 mb-4">{{ __('Basic Information') }}</h3>
        <div class="flex justify-between mb-4">
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="name" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="email" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="phone" :value="__('Phone')" />
                        <x-text-input id="phone" type="text" class="mt-1 block w-full" wire:model.defer="phone" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>
        </div>
        </div>


        <div class="flex items-center justify-end mt-4">
            <x-success-button>
                {{ __('Save Changes') }}
            </x-success-button>
        </div>
    </form>
</div>
