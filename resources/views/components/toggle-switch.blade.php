<label
  class="flex cursor-pointer select-none items-center space-x-4"
  for="{{ $id }}"
>
  <div class="relative">
    <input
      class="peer sr-only"
      id="{{ $id }}"
      type="checkbox"
      {{ $attributes }}
    />

    {{-- background --}}
    <div class="h-8 w-14 rounded-full bg-gray-500 transition-all duration-300 peer-checked:bg-cyan-400">
    </div>

    {{-- dot --}}
    <div class="absolute left-1 top-1 h-6 w-6 rounded-full bg-white transition-all duration-300 peer-checked:left-7">
    </div>
  </div>
  <span class="dark:text-gray-50">
    {{ $slot }}
  </span>
</label>
