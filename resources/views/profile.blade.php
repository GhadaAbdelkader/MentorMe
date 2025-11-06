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

        <livewire:user.admin.user-profile-viewer :userId="$userId" />
    @else

        @if (Auth::user()->role === 'admin')


            <livewire:user.admin.admin-profile />
        @endif
        @if (Auth::user()->role === 'mentee')


            <livewire:user.mentee.mentee-profile />
        @endif
        @if (Auth::user()->role === 'mentor')


            <livewire:user.mentor.mentor-profile />
        @endif

    @endisset
</x-app-layout>
