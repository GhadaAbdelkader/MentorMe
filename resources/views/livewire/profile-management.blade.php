<div class="space-y-6">
    @if (session()->has('success_message'))
        <div x-data="{ show: true }" x-show="show" x-transition:leave.opacity.duration.1500ms
             class="p-4 rounded-lg bg-green-100 border border-green-400 text-green-700 font-medium">
            {{ session('success_message') }}
        </div>
    @endif

    <form wire:submit.prevent="saveProfile" class="space-y-6">
        <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden p-6 border-l-4 border-indigo-500">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Basic Information') }}</h3>

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

        @if ($isMentor)
            <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden p-6 border-l-4 border-green-500">
                <h3 class="text-2xl font-bold text-green-700 mb-4">{{ __('Mentor Profile') }}</h3>
                <p class="text-gray-600 mb-6">{{ __('Please fill out these fields to appear in the mentees search list.') }}</p>

                <div class="mt-4">
                    <x-input-label for="specialization" :value="__('Main Specialization')" />
                    <x-text-input id="specialization" type="text" class="mt-1 block w-full" wire:model.defer="specialization" placeholder="Example: Web Development (Laravel, Vue)" />
                    <x-input-error class="mt-2" :messages="$errors->get('specialization')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="experience_years" :value="__('Years of Experience')" />
                    <x-text-input id="experience_years" type="number" class="mt-1 block w-full" wire:model.defer="experience_years" min="0" />
                    <x-input-error class="mt-2" :messages="$errors->get('experience_years')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="bio" :value="__('Bio & Skills')" />
                    <textarea id="bio" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model.defer="bio" placeholder="A short description of your skills and experience..."></textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="available_hours" :value="__('Available Hours (Description)')" />
                    <x-text-input id="available_hours" type="text" class="mt-1 block w-full" wire:model.defer="available_hours" placeholder="Example: Sunday to Thursday after 5 PM" />
                    <x-input-error class="mt-2" :messages="$errors->get('available_hours')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="linkedin_url" :value="__('LinkedIn URL')" />
                    <x-text-input id="linkedin_url" type="url" class="mt-1 block w-full" wire:model.defer="linkedin_url" placeholder="https://www.linkedin.com/in/yourprofile" />
                    <x-input-error class="mt-2" :messages="$errors->get('linkedin_url')" />
                </div>

                <div class="mt-4 flex items-center">
                    <input id="is_available" type="checkbox" wire:model.defer="is_available" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                    <x-input-label for="is_available" class="ml-2 rtl:mr-2 text-sm text-gray-600">{{ __('Currently available to accept mentorship requests') }}</x-input-label>
                </div>

            </div>
        @endif

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Save Changes') }}
            </x-primary-button>
        </div>
    </form>
</div>
