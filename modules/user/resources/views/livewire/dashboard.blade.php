
<div class="py-6">
    <div class=" mx-auto  sm:px-6 lg:px-8">

        @if (Gate::allows('isAdmin'))
            <livewire:user.admin.user-management />
        @elseif (Gate::allows('isMentor'))
            <livewire:user.mentor.mentor-profile />

        @elseif (Gate::allows('isMentee'))
            <livewire:user.mentee.mentee-profile />


        @else
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border-l-4 border-red-500">
                <div class="p-6 text-gray-900 text-center">
                    <h2 class="text-xl font-bold text-red-700">{{ __('Undefined Role') }}</h2>
                    <p class="mt-2">{{ __('Your account role is not defined. Please contact technical support.') }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
