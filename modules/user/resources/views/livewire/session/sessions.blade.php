<div class="bg-white mt-6  overflow-hidden shadow-xl sm:rounded-lg">


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('📚 My Sessions') }}
        </h2>
    </x-slot>

<div class="bg-white mt-6 flex justify-start overflow-hidden shadow-xl sm:rounded-lg border-l-4 border-green-500">


<!-- Header -->
    @include('user::livewire.partial.mentor-sidbar')


    <!-- Search & Filter -->
    <div class="bg-white w-full rounded-xl shadow p-4 space-y-4">
        <div class="flex mb-6 justify-between items-center">
            <input type="text"
                   wire:model.live="searchTerm"
                   placeholder="Search For Mentee..."
                   class="w-3/4 px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500">
            <a href="{{ route('session.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                ➕ New Session
            </a>
        </div>
        <!-- Search -->

        <!-- Filters -->
        <div class="flex gap-2 flex-wrap">
            @foreach($statuses as $key => $label)
                <button
                    wire:click="setFilter('{{ $key }}')"
                    class="px-4 py-2 rounded-lg transition
                           {{ $filterStatus === $key
                                ? 'bg-indigo-600 text-white'
                                : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    <!-- Sessions -->
    <!-- Sessions Table -->
    @if($sessions->count())
        <div class="bg-white shadow rounded-xl overflow-hidden">
            {{-- Session Flash Messages --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-300">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-300">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <table class="w-full text-center">
                <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="py-3 px-4">Mentee</th>
                    <th class="py-3 px-4">Date</th>
                    <th class="py-3 px-4">Duration</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Actions</th>
                </tr>
                </thead>

                <tbody class="divide-y">
                @foreach($sessions as $session)
                    <tr class="hover:bg-gray-50">
                        <!-- Mentee -->
                        <td class="py-3 px-4 font-semibold">
                            {{ $session->mentee->name }}
                        </td>

                        <!-- Date -->
                        <td class="py-3 px  -4">
                            {{ $session->scheduled_at->format('Y-m-d H:i') }}
                        </td>

                        <!-- Duration -->
                        <td class="py-3 px-4">
                            {{ $session->duration }} Minute
                        </td>

                        <!-- Status -->
                        <td class="py-3 px-4">
                            @if($session->status === 'scheduled')
                                <span class="bg-green-600 text-white text-xs px-2 py-1 rounded">Scheduled</span>
                            @elseif($session->status === 'modified')
                                <span class="bg-indigo-500 text-white text-xs px-2 py-1 rounded">Modified</span>
                            @elseif($session->status === 'cancelled')
                                <span class="bg-red-600 text-white text-xs px-2 py-1 rounded">Canceled</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="py-3 px-4 flex justify-center gap-2">
                            <a href="{{ route('session.show', $session->id) }}"
                               class="text-indigo-600 font-bold hover:underline">Show</a>
                            @if(auth()->user()->role === 'mentor')

                            <a href="{{ route('session.edit', $session->id) }}"
                               class="text-yellow-600 font-bold hover:underline">Modify</a>

                            <button wire:click="deleteSession({{ $session->id }})"
                                    wire:confirm="⚠️ Warning: This will permanently delete the session! Are you sure?"
                                    wire:loading.attr="disabled"
                                    class=" text-red-600 font-bold hover:underline">
                                <span wire:loading.remove>Delete</span>
                                <span wire:loading>⏳ Deleting</span>
                            </button>
                            @endif

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-6">
            {{ $sessions->links('pagination::tailwind') }}
        </div>

    @else
        <div class="bg-white rounded-xl shadow p-8 text-center space-y-4">
            <h3 class="text-xl font-semibold text-gray-900">📭 There Is No Session Here</h3>
            <p class="text-gray-600">
Start                 <a href="{{ route('session.create') }}" class="text-indigo-600 hover:underline">Create New Session</a>
            </p>
        </div>
    @endif

</div>
</div>
</div>
