<div class="bg-white mt-6  overflow-hidden shadow-xl sm:rounded-lg">


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('📚 Session Details') }}
        </h2>
    </x-slot>

    <div class="bg-white mt-6 flex justify-start overflow-hidden shadow-xl sm:rounded-lg border-l-4 border-green-500">


        <!-- Header -->
        @include('user::livewire.partial.mentor-sidbar')

    <!-- Main Card -->
        <div class="w-full p-4 space-y-4">
        <div class="bg-white shadow rounded-xl overflow-hidden mb-6">

            <!-- Header -->
            <div class="bg-green-100 text-green-700 flex justify-between items-center p-4">
                <h3 class="text-lg font-semibold">Session Details</h3>

                @if($session->status === 'scheduled')
                    <span class="bg-white text-green-600 text-sm px-3 py-1 rounded-full">Scheduled</span>
                @elseif($session->status === 'modified')
                    <span class="text-indigo-400 bg-white text-sm px-3 py-1 rounded-full">Modified</span>
                @elseif($session->status === 'cancelled')
                    <span class="text-red-600 bg-white text-sm px-3 py-1 rounded-full">Cancelled</span>
                @elseif($session->status === 'completed')
                    <span class="text-gray-600 bg-white text-sm px-3 py-1 rounded-full">Completed</span>
                @endif
            </div>

            <!-- Body -->
            <div class="p-6 space-y-6">
                <!-- Users -->
                {{-- Session Flash Messages --}}
                <div x-data="{ notifications: [] }"
                     x-on:show-notification.window="
        const notificationId = Date.now();
        notifications.push({
            id: notificationId,
            type: $event.detail.type,
            message: $event.detail.message
        });
        setTimeout(() => {
            notifications = notifications.filter(n => n.id !== notificationId);
        }, 5000);
     "
                     class=" space-y-2">

                    <template x-for="notification in notifications" :key="notification.id">
                        <div :class="{
            'bg-green-500': notification.type === 'success',
            'bg-red-500': notification.type === 'error',
            'bg-blue-500': notification.type === 'info'
        }"
                             class="text-white p-4 rounded-lg shadow-lg max-w-sm">
                            <div class="flex items-center justify-between">
                                <span x-text="notification.message"></span>
                                <button @click="notifications = notifications.filter(n => n.id !== notification.id)"
                                        class="ml-4 hover:text-gray-200">×</button>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="font-semibold mb-2 text-gray-800">Mentee</h5>
                        <p><strong>Name:</strong> {{ $session->mentee->name }}</p>
                        <p><strong>Email:</strong> {{ $session->mentee->email }}</p>
                    </div>

                    <div>
                        <h5 class="font-semibold mb-2 text-gray-800">Mentor</h5>
                        <p><strong>Name:</strong> {{ $session->mentor->name }}</p>
                        <p><strong>Email:</strong> {{ $session->mentor->email }}</p>
                    </div>
                </div>

                <hr class="border-gray-200">

                <!-- Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="font-semibold mb-2 text-gray-800">Date & Time</h5>
                        <p>{{ $session->scheduled_at->format('Y-m-d H:i') }}</p>
                        <p class="text-gray-500 text-sm">({{ $session->scheduled_at->format('l') }})</p>
                    </div>

                    <div>
                        <h5 class="font-semibold mb-2 text-gray-800">Duration</h5>
                        <p>{{ $session->duration }} Minute</p>
                    </div>
                </div>

                <hr class="border-gray-200">

                <!-- Notes -->
                @if($session->notes)
                    <div>
                        <h5 class="font-semibold mb-2 text-gray-800">Notes</h5>
                        <div class="bg-gray-50 border border-gray-200 p-4 rounded-lg">
                            {{ $session->notes }}
                        </div>
                    </div>

                    <hr class="border-gray-200">
                @endif

                <!-- Timeline -->
                <div>
                    <h5 class="font-semibold mb-4 text-gray-800"> Timeline</h5>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-3 h-3 mt-1 rounded-full bg-green-600"></div>
                            <div>
                                <strong>Created At</strong>
                                <p class="text-gray-500">{{ $session->created_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>

                        @if($session->updated_at !== $session->created_at)
                            <div class="flex items-start gap-3">
                                <div class="w-3 h-3 mt-1 rounded-full bg-indigo-400"></div>
                                <div>
                                    <strong>Last Edit</strong>
                                    <p class="text-gray-500">{{ $session->updated_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 p-4 flex flex-wrap gap-2">
                @if(auth()->user()->role === 'mentor')
                @if($session->status !== 'cancelled')
                    <a href="{{ route('session.edit', $session->id) }}"
                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
Edit                    </a>
                @endif
                    @if($session->status === 'cancelled')
                        <button wire:click="deleteSession"
                                wire:confirm="⚠️ Warning: This will permanently delete the session from the database and it can only be recovered using the undo button! Are you sure?"
                                wire:loading.attr="disabled"
                                class="bg-red-700 hover:bg-red-800 text-white px-4 py-2 rounded-lg">
                            <span wire:loading.remove>Permanent Deletion</span>
                            <span wire:loading> Deleting...</span>
                        </button>
                    @endif

                    @if($session->status === 'scheduled')
                        <button wire:click="cancelSession"
                                wire:confirm="Do you want to cancel this session?؟"
                                wire:loading.attr="disabled"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                            <span wire:loading.remove> Canceling Session</span>
                            <span wire:loading>Canceling...</span>
                        </button>
                    @endif
                    @if($session->status === 'cancelled')
                        <button wire:click="rescheduleSession({{ $session->id }})"
                                wire:confirm="Do you want to reschedule this session?"
                                wire:loading.attr="disabled"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                            Reschedule
                        </button>
                    @endif
                <button wire:click="undo"
                        onclick="return confirm('Should I back out of my last order?')"
                        class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded">
                    Undo
                </button>
                @endif

                <a href="{{ route('session.index') }}"
                   class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
Back
                </a>
            </div>
        </div>
    </div>


</div>
</div>
