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
            <div class="flex justify-start mx-auto  sm:px-6 lg:px-8" x-data="{ activeTab: 'profile' }">
                <div class="bg-white mt-4 flex justify-start overflow-hidden shadow-xl sm:rounded-lg border-l-4 border-indigo-500 w-full">
                    @include('user::livewire.partial.admin-sidbar')

                    <div class=" sm:p-8 bg-white shadow sm:rounded-lg  border-l-4 sm:px-6 lg:px-8 space-y-6 w-full">

                        <div class="bg-white shadow sm:rounded-lg p-4">
                            <nav class="flex space-x-2 rtl:space-x-reverse" aria-label="Tabs">

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
                        </div>

                        <div class="space-y-6">

                            <div x-show="activeTab === 'profile'" x-cloak>
                                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg  border-l-4 ">
                                    <livewire:user.profile.profile-photo-manager />

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
                            </div>


                            <div x-show="activeTab === 'password'" x-cloak>
                                <div class="w-full bg-white shadow-xl sm:rounded-lg overflow-hidden p-6 border-l-4 border border-gray-200">
                                    <div class="">
                                        <livewire:user.profile.update-password-form />
                                    </div>
                                </div>
                            </div>

                            <div x-show="activeTab === 'delete'" x-cloak>
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
