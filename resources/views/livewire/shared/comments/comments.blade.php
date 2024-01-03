{{-- 留言列表 --}}
<div
  class="w-full"
  id="comments"
>
  {{-- new comment will show here --}}
  <livewire:shared.comments.comment-group
    :$postId
    :$postAuthorId
    :wire:key="'group-new'"
  />

  @foreach ($IdsByFirstId as $groupId => $ids)
    <livewire:shared.comments.comment-group
      :$postId
      :$postAuthorId
      :$ids
      :$groupId
      :wire:key="'group-'.$groupId"
    />
  @endforeach

  @if ($showMoreButtonIsActive)
    <div class="mt-6 flex items-center justify-center">
      <button
        class="relative text-lg dark:text-gray-50"
        type="button"
        {{-- when click the button and update the DOM, make windows.scrollY won't change --}}
        wire:click="showMore"
      >
        <span>顯示更多</span>
        <div
          class="absolute -right-8 top-1.5"
          wire:loading
        >
          <svg
            class="h-5 w-5 animate-spin text-gray-700 dark:text-gray-50"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
          >
            <circle
              class="opacity-25"
              cx="12"
              cy="12"
              r="10"
              stroke="currentColor"
              stroke-width="4"
            ></circle>
            <path
              class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
            >
            </path>
          </svg>
        </div>
      </button>
    </div>
  @endif
</div>
