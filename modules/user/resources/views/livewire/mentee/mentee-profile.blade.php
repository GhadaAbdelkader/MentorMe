<div class="bg-white mt-6 flex justify-start overflow-hidden shadow-xl sm:rounded-lg border-l-4 border-orange-500">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mentee Profile') }}
        </h2>
    </x-slot>

    @include('user::livewire.partial.mentee-sidbar')

    <div class="bg-white w-full mt-6 w-full flex  overflow-hidden shadow-xl sm:rounded-lg border-l-4 ">
        <div class="p-6 w-full text-gray-900 relative">
            <h2 class="text-3xl font-extrabold text-orange-700 tracking-tight">{{ __('Mentee Dashboard') }}</h2>
            <p class="mt-3 text-lg text-gray-600">{{ __('Welcome we are happy to have you in our community.') }}</p>

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
                                    :class="activeTab === 'profile' ? 'bg-orange-100 text-orange-700' : 'text-gray-500 hover:text-gray-700'"
                                    class="px-3 py-2 font-medium text-sm rounded-md transition duration-150 ease-in-out">
                                {{ __('AdminProfile Management (Professional & Basic)') }}
                            </button>


                            <button @click="activeTab = 'password'"
                                    :class="activeTab === 'password' ? 'bg-orange-100 text-orange-700' : 'text-gray-500 hover:text-gray-700'"
                                    class="px-3 py-2 font-medium text-sm rounded-md transition duration-150 ease-in-out">
                                {{ __('Change Password') }}
                            </button>

                            <button @click="activeTab = 'delete'"
                                    :class="activeTab === 'delete' ? 'bg-orange-100 text-orange-700' : 'text-gray-500 hover:text-gray-700'"
                                    class="px-3 py-2 font-medium text-sm rounded-md transition duration-150 ease-in-out">
                                {{ __('Delete Account') }}
                            </button>

                        </nav>
                        <div x-show="activeTab === 'profile'" x-cloak>
                        <form wire:submit.prevent="saveProfile" class="space-y-6">
                            <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden p-6 border-l-4 mt-6 w-full">
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
                            <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden p-6 border-l-4 border">
                                <h3 class="text-2xl font-bold mb-4 border-b w-fit">{{ __('Professional Information') }}</h3>
                                <p class="text-gray-600 mb-6">{{ __('Please fill out these fields to appear in the mentors search list.') }}</p>

                                <div class="mt-4">
                                    <x-input-label for="interests" :value="__('Interests')" />
                                    <x-text-input id="interests" type="text" class="mt-1 block w-full" wire:model.defer="interests" placeholder="Example: Web Development (Laravel, Vue)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('interests')" />
                                </div>


                                <div class="mt-4">
                                    <x-input-label for="goals" :value="__('Goals')" />
                                    <textarea id="goals" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model.defer="goals" placeholder="A short description of your goals ..."></textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('goals')" />
                                </div>



                                <div class="mt-4">
                                    <x-input-label for="current_level" :value="__('Current Level ')" />
                                    <x-text-input id="current_level" type="text" class="mt-1 block w-full" wire:model.defer="current_level" placeholder="Your current level" />
                                    <x-input-error class="mt-2" :messages="$errors->get('current_level')" />
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




<div class="p-6 text-gray-900 w-3/12 shadow-xl sm:rounded-lg border-l-4">
    <livewire:user.shared.profile-completion />
</div>
</div>
