<div
  class="relative flex min-h-screen flex-col justify-between selection:bg-emerald-300 selection:text-emerald-900 dark:selection:bg-indigo-300 dark:selection:text-indigo-900"
>
  <x-background />

  <livewire:shared.header />

  {{ $slot }}

  <x-layouts.footer />
</div>
