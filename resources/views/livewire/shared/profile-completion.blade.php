    <div class="bg-white   overflow-hidden ">
        <h3 class="text-2xl font-bold text-black-900 mb-4 border-b">{{ __('Complete Your Profile') }}</h3>

        <div class="mb-3 item-center text-center">
                <div class="relative flex items-center justify-center ">
                    <svg class="transform -rotate-90 w-32 h-32">
                        @php
                            $radius = 50;
                            $circumference = 2 * M_PI * $radius;
                            $offset = $circumference - ($this->completionPercentage / 100) * $circumference;
                        @endphp

                        <circle
                            cx="64"
                            cy="64"
                            r="{{ $radius }}"
                            stroke="gray"
                            stroke-width="10"
                            fill="none"
                            class="text-gray-300"
                        ></circle>
                        <circle
                            cx="64"
                            cy="64"
                            r="{{ $radius }}"
                            stroke="#16a34a"
                            stroke-width="10"
                            fill="none"
                            stroke-linecap="round"
                            stroke-dasharray="{{ $circumference }}"
                            stroke-dashoffset="{{ $offset }}"
                            class="transition-all duration-500 ease-out"
                        ></circle>
                    </svg>

                    <span class="absolute text-xl font-bold text-green-700">
                        {{ $this->completionPercentage }}%
                    </span>
                </div>

                <p class="text-black-600 mt-2 text-sm text-center text-lg">Profile completion</p>

                <div class="mt-2 text-sm text-black-600 text-lg">{{ $this->completionPercentage }}% complete</div>
            </div>

            <ul class="space-y-2 mt-6 border px-4 py-4 text-lg rounded-lg border-gray-200">
                @foreach ($this->profile?->completionItems() ?? [] as $item)
                    <li class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full {{ $item['filled'] ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                        <span class="{{ $item['filled'] ? 'line-through text-gray-500' : '' }}">{{ $item['label'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

