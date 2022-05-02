<div
  x-data
  class="absolute top-0 hidden w-14 h-full xl:block left-[102%]"
>
  <div class="sticky top-1/2 -translate-y-1/2 flex flex-col items-center justify-center space-y-3">
    {{-- Home --}}
    <a
      href="{{ route('posts.index') }}"
      class="inline-flex items-center justify-center w-14 h-14 transition duration-150 ease-in-out border border-transparent bg-green-500 group rounded-xl text-gray-50 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300"
    >
        <span class="text-2xl transition duration-150 ease-in group-hover:scale-125 group-hover:rotate-12">
          <i class="bi bi-house-fill"></i>
        </span>
    </a>

    <!-- Facebook share button -->
    <button
      type="button"
      data-sharer="facebook"
      data-hashtag="{{ config('app.name') }}"
      data-url="{{ request()->fullUrl() }}"
      class="inline-flex items-center justify-center w-14 h-14 transition duration-150 ease-in-out bg-blue-600 border border-transparent group rounded-xl text-gray-50 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300"
    >
        <span class="text-2xl transition duration-150 ease-in group-hover:scale-125 group-hover:rotate-12">
          <i class="bi bi-facebook"></i>
        </span>
    </button>

    <!-- Twitter share button -->
    <button
      type="button"
      data-sharer="twitter"
      data-title="{{ $post->title }}"
      data-hashtags="{{ config('app.name') }}"
      data-url="{{ request()->fullUrl() }}"
      class="inline-flex items-center justify-center w-14 h-14 transition duration-150 ease-in-out bg-sky-400 border border-transparent group rounded-xl text-gray-50 active:bg-sky-600 focus:outline-none focus:border-sky-600 focus:ring ring-sky-300"
    >
        <span class="text-2xl transition duration-150 ease-in group-hover:scale-125 group-hover:rotate-12">
          <i class="bi bi-twitter"></i>
        </span>
    </button>

    {{-- 編輯文章 --}}
    @if (auth()->id() === $post->user_id)
      <div class="h-[2px] w-4/5 bg-gray-300 dark:bg-gray-600"></div>

      <a
        href="{{ route('posts.edit', ['post' => $post->id]) }}"
        class="inline-flex items-center justify-center w-14 h-14 transition duration-150 ease-in-out border border-transparent bg-emerald-500 group rounded-xl text-gray-50 active:bg-emerald-700 focus:outline-none focus:border-emerald-700 focus:ring ring-emerald-300"
      >
        <span class="text-2xl transition duration-150 ease-in group-hover:scale-125 group-hover:-rotate-12">
          <i class="bi bi-pencil-fill"></i>
        </span>
      </a>

      {{-- 軟刪除 --}}
      <button
        x-on:click="
          if (confirm('您確定標記此文章為刪除狀態嗎？（30 天內還可以還原）')) {
            document.getElementById('soft-delete-post').submit()
          }
        "
        type="button"
        class="inline-flex items-center justify-center w-14 h-14 transition duration-150 ease-in-out bg-orange-500 border border-transparent group rounded-xl text-gray-50 active:bg-orange-700 focus:outline-none focus:border-orange-700 focus:ring ring-orange-300"
      >
        <span class="text-2xl transition duration-150 ease-in group-hover:scale-125 group-hover:rotate-12">
          <i class="bi bi-file-earmark-x-fill"></i>
        </span>
      </button>
    @endif

  </div>
</div>
