<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('User Management') }}
    </h2>
</x-slot>
<div class="bg-white mt-4 flex justify-start overflow-hidden shadow-xl sm:rounded-lg border-l-4 border-indigo-500">
    @include('user::livewire.partial.admin-sidbar')


<div class="bg-white w-full overflow-hidden shadow-xl sm:rounded-lg border-l-4 ">
    <div class="p-6 text-gray-900">
        <h2 class="text-3xl font-extrabold text-indigo-700 tracking-tight">{{ __('Admin Dashboard') }}</h2>
        <p class="mt-3 text-lg text-gray-600">{{ __('Welcome, Admin. You have full system management access.') }}</p>
<div>
    <div
        x-data="{ showToast: false, message: '', type: '' }"
        @toast.window="
        message = $event.detail.message;
        type = $event.detail.type;
        showToast = true;
        setTimeout(() => showToast = false, 3000);
    "
    >
        <div
            x-show="showToast"
            x-transition
            :class="type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
            class="p-4 mb-4 text-sm text-700  rounded-lg shadow z-50">
            <span x-text="message"></span>
        </div>



    <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden">
        <div class=" py-2 px-4 flex justify-between my-4 sm:rounded-lg overflow-hidden bg-gray-50 border-b border-gray-200">
            <div class="  ">
                <h3 class="text-xl font-semibold text-gray-800">{{ __('Users List') }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ __('Review and manage the status of all users.') }}</p>
            </div>
            <div class="flex justify-end gap-3 mb-4">
                <!-- Search -->
                <input type="text" wire:model="searchName" placeholder="Search by name"
                       class="border border-gray-300 rounded-lg px-3 w-1/3">

                <!-- Dropdown Filter -->

                <select wire:model="filterRole"
                        class="border border-gray-300 rounded-lg px-3 pr-8 ">
                    <option value="">Choose Role</option>
                    <option value="mentor">Mentor</option>
                    <option value="mentee">Mentee</option>
                </select>
                @if ($filterRole === 'mentor')
                    <select wire:model="filterExperience" class="border border-gray-300 rounded-lg px-3 ">
                        <option value="">All Experience Levels</option>
                        <option value="junior">0–2 years</option>
                        <option value="mid">3–5 years</option>
                        <option value="senior">More than 5 years</option>
                    </select>
                @endif

                <!-- Apply Button -->
                <button wire:click="$refresh"
                        class="bg-orange-500 text-white rounded-lg px-4 hover:bg-orange-600">
                    Apply
                </button>
            </div>
        </div>


        @if ($users->isEmpty())
            <div class="p-8 text-center text-gray-500">
                <p class="text-lg">{{ __('No mentors available at the moment.') }}</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="text-align: left;">
                            #
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="text-align: left;">
                            {{ __('Name') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="text-align: left;">
                            {{ __('Email') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="text-align: left;">
                            {{ __('Status') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="text-align: center;">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($users as $user)

                        <tr>
                            <td class="px-6 py-4 text-left text-sm text-gray-900">
                                {{ $users->firstItem() + $loop->index }}
                            </td>
                            <td class="px-6 py-4 text-left whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4 text-left whitespace-nowrap text-sm text-gray-500">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 text-left whitespace-nowrap text-sm">
                                @php
                                    $statusClass = match($user->status) {
                                        'active' => 'bg-green-100 text-green-800',
                                        'inactive' => 'bg-red-100 text-red-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ __(\Illuminate\Support\Str::ucfirst($user->status)) }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 justify-between  whitespace-nowrap text-sm font-medium flex items-center space-x-2 rtl:space-x-reverse">

                                <!-- View Details -->
                                <a href="{{ route('admin.mentor.profile', $user->id) }}"
                                   class="text-blue-600 hover:text-blue-900 transition duration-150 ease-in-out font-bold inline-block">
                                    {{ __('View Details') }}
                                </a>

                                <!-- Activate -->
                                @if ($user->status !== 'active')
                                    <button wire:click="updateStatus({{ $user->id }}, 'active')"
                                            wire:confirm="{{ __('Are you sure you want to activate this ' . $user->role . '?') }}"
                                            class="text-green-600 hover:text-green-900 transition duration-150 ease-in-out font-bold inline-block">
                                        {{ __('Activate') }}
                                    </button>
                                @endif

                                <!-- Deactivate -->
                                @if ($user->status !== 'inactive')
                                    <button wire:click="updateStatus({{ $user->id }}, 'inactive')"
                                            wire:confirm="{{ __('Are you sure you want to deactivate this ' . $user->role . '?') }}"
                                            class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out font-bold inline-block">
                                        {{ __('Deactivate') }}
                                    </button>
                                @endif
                                @if ($user->status !== 'pending')
                                    <button wire:click="updateStatus({{ $user->id }}, 'pending')"
                                            wire:confirm="{{ __('Are you sure you want to pending this ' . $user->role . '?') }}"
                                            class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out font-bold inline-block">
                                        {{ __('Pending') }}
                                    </button>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="mt-4 mx-6 mb-4 py-2 px-4 bg-gray-50 border rounded-lg">
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
</div>
    </div>
</div>

</div>
