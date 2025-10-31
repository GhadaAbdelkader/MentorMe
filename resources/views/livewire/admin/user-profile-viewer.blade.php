
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('User AdminProfile') }}
    </h2>
</x-slot>
<div class="bg-white mt-6 flex justify-start overflow-hidden shadow-xl sm:rounded-lg border-l-4 border-indigo-500">
    <livewire:partial.admin-sidbar />
<div class="py-12 bg-white w-full overflow-hidden shadow-xl sm:rounded-lg border-l-4 ">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl sm:rounded-lg p-6 relative">

            <h1 class="text-3xl font-bold text-indigo-800 mb-6 border-b pb-2">
                {{ __('AdminProfile of:') }} {{ $user->name }}
                <span class="text-sm font-medium text-gray-500 block">{{ __('Role:') }} {{ __(\Illuminate\Support\Str::ucfirst($user->role)) }}</span>
            </h1>


            <!-- massages -->
            @if (session()->has('message'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 4000)"
                    class="absolute top-0 right-0 p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg"
                    role="alert">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error_message'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 4000)"
                    class="absolute top-0 right-0 p-4 p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg"
                    role="alert">
                    {{ session('error_message') }}
                </div>
            @endif
            <!-- massages -->


            <div class="w-full bg-white sm:rounded-lg overflow-hidden  ">
                <div class="rounded-lg">
                    <div>
                        <div class="flex justify-center py-4 mb-6 ">
                            <img src="{{ Auth::user()->profile_photo_path
                                        ? asset('storage/' . Auth::user()->profile_photo_path)
                                        : asset('storage/profile-photos/Male.jpg') }}"
                                 alt="{{ Auth::user()->name }}"
                                 class="h-36 w-36 object-cover rounded-full border-2 border-gray-200 shadow-xl transition-all duration-300">
                        </div>
{{--                        @if ($isAdminViewing && $user->role === 'mentor')--}}
                            <div class="flex justify-between items-center mb-2 p-4 border rounded-lg
                    @if($user->status === 'pending') bg-yellow-50 border-yellow-300
                    @elseif($user->status === 'active') bg-green-50 border-green-300
                    @else bg-red-50 border-red-300 @endif">

                                <p class="text-lg font-semibold text-gray-800">
                                    {{ __("{$user->name}'s Status:") }}
                                    <span class="px-2 py-1 ml-2 text-sm font-medium rounded-full
                                        @if($user->status === 'active') bg-green-100 text-green-800
                                        @elseif($user->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ __(Str::ucfirst($user->status)) }}
                                    </span>
                                </p>

                                <div class="space-x-4 rtl:space-x-reverse">
                                    @if ($user->status !== 'active')
                                        <button wire:click="updateStatus('active')"
                                                wire:confirm="{{ __('Are you sure you want to activate this account?') }}"
                                                class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition duration-150">
                                            {{ __('Approve & Activate') }}
                                        </button>
                                    @endif
                                    @if ($user->status !== 'inactive')
                                        <button wire:click="updateStatus('inactive')"
                                                wire:confirm="{{ __('Are you sure you want to reject and deactivate this account?') }}"
                                                class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition duration-150">
                                            {{ __('Reject & Deactivate') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
{{--                        @endif--}}

                        <div class="border border-gray-200 sm:rounded-lg  p-6 mt-2  shadow-xl ">
                            <h2 class="text-xl font-semibold text-gray-700 mb-3 border-b w-fit pb-1">{{ __('Basic Information') }}</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3   ">
                                <p class="py-3 px-3 sm:rounded-lg mb-2 border border-gray-200"><span class="font-medium">{{ __('Full Name:') }}</span> {{ $user->name }}</p>
                                <p class="py-3 px-3 sm:rounded-lg mb-2 border border-gray-200"><span class="font-medium">{{ __('Email:') }}</span> {{ $user->email }}</p>
                                <p class="py-3 px-3 sm:rounded-lg mb-2 border border-gray-200"><span class="font-medium">{{ __('Phone:') }}</span> {{ $user->phone }}</p>
                                <p class="py-3 px-3 sm:rounded-lg mb-2 border border-gray-200 "><span class="font-medium ">{{ __('Status:') }}</span> {{ $user->status }}</p>
                                <p class="py-3 px-3 sm:rounded-lg mb-2 border border-gray-200"><span class="font-medium">{{ __('Registration Date:') }}</span> {{ $user->created_at->format('Y-m-d') }}</p>
                            </div>
                            <div class="mt-8">
                                @if ($user->role === 'mentor')
                                    <div>
                                        <h2 class="text-xl w-fit font-semibold text-gray-700 mb-3 border-b pb-1">{{ __('Professional AdminProfile') }}</h2>

                                        @if ($this->isMentorProfileFilled())
                                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3   ">
                                                    <p class="py-3 px-3 sm:rounded-lg mb-2 border border-gray-200"><span class="font-medium">{{ __('specialization:') }}</span> {{ $user->mentorProfile->specialization ?? 'Not specified' }}</p>
                                                    <p class="py-3 px-3 sm:rounded-lg mb-2 border border-gray-200"><span class="font-medium">{{ __('Years Of Experience :') }}</span> {{ $user->mentorProfile->experience_years ?? 'No description' }}</p>
                                                    <p class="py-3 px-3 sm:rounded-lg mb-2 border border-gray-200"><span class="font-medium">{{ __('Available for Mentorship:') }}</span>
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($user->is_available ?? false) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ ($user->is_available ?? false) ? __('Yes') : __('No') }}
                                                        </span>
                                                    </p>
                                            </div>
                                            <p class="py-3 px-3 sm:rounded-lg mb-2 border border-gray-200"><span class="font-medium">{{ __('Bio:') }}</span> {{ $user->mentorProfile->bio ?? 'No description' }}</p>

                                        @else
                                            <p class="text-gray-500">{{ __('The professional profile has not been completed yet.') }}</p>
                                        @endif
                                    </div>
                                @endif

                            </div>

                        </div>
                        </div>
                </div>

                </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ url()->previous() }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150">
                    {{ __('Go Back') }}
                </a>
            </div>
        </div>
    </div>
</div>
</div>
