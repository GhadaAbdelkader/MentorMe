<button {{ $attributes->merge(['type' => 'submit', 'class' => 'px-4 py-2  text-white font-semibold rounded-lg shadow-md inline-flex items-center bg-emerald-600 border border-transparent rounded-md  text-xs text-white uppercase tracking-widest hover:bg-emerald-500 focus:bg-emerald-500 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
