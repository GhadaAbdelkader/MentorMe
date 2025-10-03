<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl sm:rounded-lg p-6">

            <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">
                {{ __('Profile of:') }} {{ $user->name }}
                <span class="text-sm font-medium text-gray-500 block">{{ __('Role:') }} {{ __(\Illuminate\Support\Str::ucfirst($user->role)) }}</span>
            </h1>

            @if (session()->has('message'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">{{ session('message') }}</div>
            @endif
            @if (session()->has('error_message'))
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">{{ session('error_message') }}</div>
            @endif

            @if ($isAdminViewing && $user->role === 'mentor')
                <div class="flex justify-between items-center mb-6 p-4 border rounded-lg
                    @if($user->status === 'pending') bg-yellow-50 border-yellow-300
                    @elseif($user->status === 'active') bg-green-50 border-green-300
                    @else bg-red-50 border-red-300 @endif">

                    <p class="text-lg font-semibold
                        @if($user->status === 'pending') text-yellow-800
                        @elseif($user->status === 'active') text-green-800
                        @else text-red-800 @endif">
                        {{ __('Mentor Status (for Admin):') }} {{ __(\Illuminate\Support\Str::ucfirst($user->status)) }}
                    </p>

                    <div class="space-x-4 rtl:space-x-reverse">
                        @if ($user->status !== 'active')
                            <button wire:click="updateStatus('active')"
                                    wire:confirm="{{ __('Are you sure you want to activate this mentor?') }}"
                                    class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition duration-150">
                                {{ __('Approve & Activate') }}
                            </button>
                        @endif
                        @if ($user->status !== 'inactive')
                            <button wire:click="updateStatus('inactive')"
                                    wire:confirm="{{ __('Are you sure you want to reject and deactivate this mentor?') }}"
                                    class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition duration-150">
                                {{ __('Reject & Deactivate') }}
                            </button>
                        @endif
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="p-4 border rounded-lg">
                    <h2 class="text-xl font-semibold text-gray-700 mb-3 border-b pb-1">{{ __('Basic Information') }}</h2>
                    <p class="mb-2"><span class="font-medium">{{ __('Email:') }}</span> {{ $user->email }}</p>
                    <p class="mb-2"><span class="font-medium">{{ __('Registration Date:') }}</span> {{ $user->created_at->format('Y-m-d') }}</p>
                </div>

                @if ($user->role === 'mentor')
                    <div class="p-4 border rounded-lg">
                        <h2 class="text-xl font-semibold text-gray-700 mb-3 border-b pb-1">{{ __('Professional Profile') }}</h2>

                        @if ($this->isMentorProfileFilled())
                            <p class="mb-2"><span class="font-medium">{{ __('Skills:') }}</span> {{ $user->mentor->mentor_skills ?? 'Not specified' }}</p>
                            <p class="mb-2"><span class="font-medium">{{ __('Experience & Description:') }}</span> {{ $user->mentor->mentor_description ?? 'No description' }}</p>
                            <p class="mb-2"><span class="font-medium">{{ __('Available for Mentorship:') }}</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($user->is_available ?? false) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ($user->is_available ?? false) ? __('Yes') : __('No') }}
                                </span>
                            </p>
                        @else
                            <p class="text-gray-500">{{ __('The professional profile has not been completed yet.') }}</p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="mt-8">
                <a href="{{ url()->previous() }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150">
                    {{ __('Go Back') }}
                </a>
            </div>
        </div>
    </div>
</div>
