
<div class="bg-white mt-6 flex justify-start overflow-hidden shadow-xl sm:rounded-lg border-l-4 border-green-500">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mentor Profile') }}
        </h2>
    </x-slot>

    @include('user::livewire.partial.mentor-sidbar')




<div class="bg-white  flex w-9/12 overflow-hidden shadow-xl sm:rounded-lg border-l-4 ">
    <div class="p-6 w-full text-gray-900 relative">
        <h2 class="text-3xl font-extrabold text-green-700 tracking-tight">{{ __('Mentor Dashboard') }}</h2>
        <p class="mt-3 text-lg text-gray-600">{{ __('Welcome we are happy to have you in our community.') }}</p>


        <div class="space-y-6 w-full">
        @if (session()->has('success_message'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 4000)"
                class="absolute top-6 right-0 p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg"
                role="alert">
                {{ session('success_message') }}
            </div>
        @endif
            <div class="flex w-full justify-start" x-data="{ activeTab: 'profile' }">
                <div class="bg-white w-full mt-6 flex justify-start overflow-hidden sm:rounded-lg  w-full">
                        <div class="bg-white w-full mt-6 shadow sm:rounded-lg p-4">
                            <nav class="flex space-x-2 rtl:space-x-reverse border py-2 ps-2 rounded-lg" aria-label="Tabs">

                                <button @click="activeTab = 'profile'"
                                        :class="activeTab === 'profile' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700'"
                                        class="px-3 py-2 font-medium text-sm rounded-md transition duration-150 ease-in-out">
                                    {{ __('AdminProfile Management (Professional & Basic)') }}
                                </button>


                                <button @click="activeTab = 'password'"
                                        :class="activeTab === 'password' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700'"
                                        class="px-3 py-2 font-medium text-sm rounded-md transition duration-150 ease-in-out">
                                    {{ __('Change Password') }}
                                </button>

                                <button @click="activeTab = 'delete'"
                                        :class="activeTab === 'delete' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700'"
                                        class="px-3 py-2 font-medium text-sm rounded-md transition duration-150 ease-in-out">
                                    {{ __('Delete Account') }}
                                </button>

                            </nav>
                        <div x-show="activeTab === 'profile'" x-cloak>
                             <form wire:submit.prevent="saveProfile" class="space-y-6">
        <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden p-6 border-l-4 border mt-6 ">
            <h3 class="text-2xl font-bold text-gray-900 mb-4 border-b w-fit">{{ __('Basic Information') }}</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3   ">

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

            <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden p-6 border-l-4 border">
                <h3 class="text-2xl font-bold mb-4 border-b w-fit">{{ __('Professional Information') }}</h3>
                <p class="text-gray-600 mb-6">{{ __('Please fill out these fields to appear in the mentors search list.') }}</p>

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

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Save Changes') }}
            </x-primary-button>
        </div>
    </form>
                        </div>
                        <div class="mt-6" x-show="activeTab === 'password'" x-cloak>
                            <div class="w-full bg-white shadow-xl sm:rounded-lg overflow-hidden p-6 border-l-4 border border-gray-200">
                                <div class="">
                                    <livewire:user.profile.update-password-form />
                                </div>
                            </div>
                        </div>

                        <div class="mt-6" x-show="activeTab === 'delete'" x-cloak>
                            <div class="w-full bg-white shadow-xl sm:rounded-lg overflow-hidden p-6 border-l-4 border border-gray-200">
                                <div class="">
                                    <livewire:user.profile.delete-user-form />
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    </div>

</div>



    <div class="p-6 text-gray-900 w-3/12 shadow-xl sm:rounded-lg border-l-4">
        <livewire:user.shared.profile-completion />
    </div>
</div>
