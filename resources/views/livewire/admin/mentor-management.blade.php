<div>
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
             class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif
    @if (session()->has('error_message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
             class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
            <span class="font-medium">{{ session('error_message') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden">
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800">{{ __('Mentors List') }}</h3>
            <p class="text-sm text-gray-500 mt-1">{{ __('Review and manage the status of all mentors.') }}</p>
        </div>

        @if ($mentors->isEmpty())
            <div class="p-8 text-center text-gray-500">
                <p class="text-lg">{{ __('No mentors available at the moment.') }}</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Name') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Email') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Status') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($mentors as $mentor)
                        <tr>
                            <td class="px-6 py-4 text-end whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $mentor->name }}
                            </td>
                            <td class="px-6 py-4 text-end whitespace-nowrap text-sm text-gray-500">
                                {{ $mentor->email }}
                            </td>
                            <td class="px-6 py-4 text-end whitespace-nowrap text-sm">
                                @php
                                    $statusClass = match($mentor->status) {
                                        'active' => 'bg-green-100 text-green-800',
                                        'inactive' => 'bg-red-100 text-red-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ __(\Illuminate\Support\Str::ucfirst($mentor->status)) }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 justify-end whitespace-nowrap text-sm font-medium flex items-center space-x-2 rtl:space-x-reverse">

                                <!-- View Details -->
                                <a href="{{ route('admin.mentor.profile', $mentor->id) }}"
                                   class="text-blue-600 hover:text-blue-900 transition duration-150 ease-in-out font-bold inline-block">
                                    {{ __('View Details') }}
                                </a>

                                <!-- Activate -->
                                @if ($mentor->status !== 'active')
                                    <button wire:click="updateStatus({{ $mentor->id }}, 'active')"
                                            wire:confirm="{{ __('Are you sure you want to activate this mentor?') }}"
                                            class="text-green-600 hover:text-green-900 transition duration-150 ease-in-out font-bold inline-block">
                                        {{ __('Activate') }}
                                    </button>
                                @endif

                                <!-- Deactivate -->
                                @if ($mentor->status !== 'inactive')
                                    <button wire:click="updateStatus({{ $mentor->id }}, 'inactive')"
                                            wire:confirm="{{ __('Are you sure you want to deactivate this mentor?') }}"
                                            class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out font-bold inline-block">
                                        {{ __('Deactivate') }}
                                    </button>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
