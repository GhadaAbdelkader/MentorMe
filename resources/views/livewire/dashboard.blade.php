<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if (Gate::allows('isAdmin'))
            {{-- If the user is an Admin --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border-l-4 border-indigo-500">
                <div class="p-6 text-gray-900">
                    <h2 class="text-3xl font-extrabold text-indigo-700 tracking-tight">{{ __('Admin Dashboard') }}</h2>
                    <p class="mt-3 text-lg text-gray-600">{{ __('Welcome, Admin. You have full system management access.') }}</p>
                    <livewire:admin.mentor-management />
                </div>
            </div>

        @elseif (Gate::allows('isMentor'))
            {{-- If the user is a Mentor --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border-l-4 border-green-500">
                <div class="p-6 text-gray-900">
                    <h2 class="text-3xl font-extrabold text-green-700 tracking-tight">{{ __('Mentor Dashboard') }}</h2>
                    <p class="mt-3 text-lg text-gray-600">{{ __('View mentees, manage your schedule, and handle new mentorship requests.') }}</p>

                </div>
            </div>

        @elseif (Gate::allows('isMentee'))
            {{-- If the user is a Mentee --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border-l-4 border-blue-500">
                <div class="p-6 text-gray-900">
                    <h2 class="text-3xl font-extrabold text-blue-700 tracking-tight">{{ __('Mentee Dashboard') }}</h2>
                    <p class="mt-3 text-lg text-gray-600">{{ __('Welcome, Mentee. Start by finding a mentor or check your mentorship requests.') }}</p>

                </div>
            </div>

        @else
            {{-- If the role is not recognized --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border-l-4 border-red-500">
                <div class="p-6 text-gray-900 text-center">
                    <h2 class="text-xl font-bold text-red-700">{{ __('Undefined Role') }}</h2>
                    <p class="mt-2">{{ __('Your account role is not defined. Please contact technical support.') }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
