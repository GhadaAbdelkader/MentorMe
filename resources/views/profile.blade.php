<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <!-- Set page title based on display mode -->
            @isset($userId)
                {{ __('Review Mentor AdminProfile') }}
            @else
                {{ __('My AdminProfile') }}
            @endisset
        </h2>
    </x-slot>

    @isset($userId)
        <livewire:admin.user-profile-viewer :userId="$userId" />
    @else
        <!-- Default mode: show the logged-in user's profile -->
        <div class="flex justify-start mx-auto  sm:px-6 lg:px-8" x-data="{ activeTab: 'profile' }">
            <div class="bg-white mt-6 flex justify-start overflow-hidden shadow-xl sm:rounded-lg border-l-4 border-indigo-500 w-full">

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
                                <!-- Default component for the current user -->
                                @if (Auth::user()->role === 'admin')
                                    <livewire:admin.profile />
                                @endif
                            </div>
                        </div>


                        <div x-show="activeTab === 'password'" x-cloak>
                            <div class="w-full bg-white shadow-xl sm:rounded-lg overflow-hidden p-6 border-l-4 border border-gray-200">
                                <div class="">
                                    <livewire:profile.update-password-form />
                                </div>
                            </div>
                        </div>

                        <div x-show="activeTab === 'delete'" x-cloak>
                            <div class="w-full bg-white shadow-xl sm:rounded-lg overflow-hidden p-6 border-l-4 border border-gray-200">
                                <div class="">
                                    <livewire:profile.delete-user-form />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endisset
</x-app-layout>
