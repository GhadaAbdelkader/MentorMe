<section class="space-y-6 relative border-b-2 border-gray-200 pb-3 mb-2">
<div class="flex justify-start">
    <div class=" flex items-start gap-6 rtl:gap-4">
        <img src="{{ Auth::user()->profile_photo_path
            ? asset('storage/' . Auth::user()->profile_photo_path)
            : asset('storage/profile-photos/Male.jpg') }}"
             alt="{{ Auth::user()->name }}"
             class="h-24 w-24 object-cover rounded-full border-2 border-gray-200 shadow-xl transition-all duration-300">


    @if ($photo)
            <div class="flex flex-col items-center">
                <span class="h-24 w-24 object-cover rounded-full border-2 border-gray-200 shadow-xl transition-all duration-300 block bg-cover bg-no-repeat bg-center "
                      style="background-image: url('{{ $photo->temporaryUrl() }}');">
                </span>
                <p class="text-xs font-medium text-indigo-500 mt-2">{{ __('New Preview') }}</p>
            </div>
        @endif
    </div>
    <div class="ml-4 px-6">
        <header >
            <h2 class="text-xl font-semibold text-gray-900">
                {{ __('AdminProfile Picture') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('You can upload, update, or delete your profile picture.') }}
            </p>
        </header>
        <form wire:submit.prevent="savePhoto" class="mt-6 space-y-4">
            <div>
                <input type="file" wire:model="photo" id="photo"
                       class="mt-1 block w-full text-sm text-gray-500
                          file:py-2 file:px-4 file:rounded-full file:border-0
                          file:text-sm file:font-semibold
                          file:bg-indigo-50 file:text-indigo-700
                          hover:file:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       accept="image/*">

                <div wire:loading wire:target="photo" class="text-sm font-medium text-indigo-600 mt-2">
                    {{ __('Uploading...') }}
                </div>

                @error('photo') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit" @if (!$photo) disabled @endif
                class="px-4 py-2 bg-emerald-600 text-white font-semibold rounded-lg shadow-md hover:bg-emerald-700 transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed">
                    {{ __('Save New Picture') }}
                </button>

                @if (Auth::user()->profile_photo_path)
                    <button type="button" wire:click="deletePhoto" wire:confirm="{{ __('Are you sure you want to delete your current picture?') }}"
                            class="px-4 py-2 text-sm font-medium text-red-600 border border-red-300 rounded-lg shadow-sm hover:bg-red-50 hover:text-red-700 transition duration-150 ease-in-out">
                        {{ __('Delete Current Picture') }}
                    </button>
                @endif
            </div>
        </form>

    </div>


</div>
    <div class="absolute top-0 right-0">
        @if (session()->has('photo_success'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 4000)"
                class="p-3 text-sm font-medium text-green-700 bg-green-100 rounded-lg shadow-sm"
                role="alert">
                {{ session('photo_success') }}
            </div>
        @endif

        @if (session()->has('photo_deleted'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 4000)"
                class="p-3 text-sm font-medium text-red-700 bg-red-100 rounded-lg shadow-sm"
                role="alert">
                {{ session('photo_deleted') }}
            </div>
        @endif

    </div>
</section>
