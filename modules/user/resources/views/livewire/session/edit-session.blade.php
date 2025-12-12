<div class="bg-white mt-6  overflow-hidden shadow-xl sm:rounded-lg">


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('📚 Edit Session ') }}
        </h2>
    </x-slot>

    <div class="bg-white mt-6 flex justify-start overflow-hidden shadow-xl sm:rounded-lg border-l-4 border-green-500">


        <!-- Header -->
        @include('user::livewire.partial.mentor-sidbar')

        <!-- Main Card -->
        <div class="w-full p-4 space-y-4">

    <div class="bg-white rounded-xl shadow">

        <div class="bg-green-100 text-green-700 p-6 rounded-t-xl">
            <h2 class="text-2xl font-bold">Edit Session</h2>
        </div>
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

        <div class="bg-white border-l-4 border-green-600 p-4 m-6">
            <p class="text-sm text-gray-700">
                <strong> Mentee:</strong> {{ $session->mentee->name }}<br><br>
                <strong>Status:</strong>
                @if($session->status === 'scheduled')
                    <span class="text-white bg-green-600 text-xs px-2 py-1 rounded">Scheduled</span>
                @elseif($session->status === 'modified')
                    <span class="text-white bg-indigo-600 text-xs px-2 py-1 rounded">Modified</span>
                @endif
            </p>
        </div>

        <form wire:submit="updateSession" class="p-6 space-y-6">

            <!-- Date -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                  Date & Time
                </label>
                <input type="datetime-local"
                       wire:model="scheduled_at"
                       min="{{ $minDateTime }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500">

                @error('scheduled_at')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Duration -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                   Duration(Minute)
                </label>
                <input type="number"
                       wire:model="duration"
                       min="15"
                       max="480"
                       step="15"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500">

                @error('duration')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                    Notes
                </label>
                <textarea wire:model="notes"
                          rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500"></textarea>

                @error('notes')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
                <button type="submit"
                        class="bg-green-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg ">
                    Save
                </button>

                <button type="button"
                        wire:click="undo"
                        class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg">
                    Undo
                </button>

                <a href="{{ route('session.show', $session->id) }}"
                   class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">
                   Back
                </a>
            </div>

        </form>

    </div>

</div>
