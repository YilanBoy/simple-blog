@script
<script>
  Alpine.data('comments', () => ({
    orderDropdownIsOpen: false,
    openOrderDropdown() {
      this.orderDropdownIsOpen = true;
    },
    closeOrderDropdown() {
      this.orderDropdownIsOpen = false;
    },
    changeOrder() {
      this.$wire.changeOrder(this.$el.dataset.order);
      this.orderDropdownIsOpen = false;
    },
    openCreateCommentModal() {
      this.$dispatch('open-create-comment-modal', {
        parentId: null,
        replyTo: null
      });
    }
  }));
</script>
@endscript

@php
  use App\Enums\CommentOrder;
@endphp

<div
  class="w-full"
  id="comments"
  x-data="comments"
>
  <div class="mt-6 w-full">
    <div class="flex justify-between">
      {{-- show comments count --}}
      <div class="flex items-center justify-center gap-6">
        <div class="flex items-center gap-2 dark:text-gray-50">
          <x-icon.chat-square-text class="size-5" />
          <span>{{ $commentCounts }} 則留言</span>
        </div>

        <div class="relative inline-block text-left">
          <div>
            <button
              class="inline-flex w-full items-center justify-center gap-2 text-gray-900 dark:text-gray-50"
              type="button"
              x-on:click="openOrderDropdown"
            >
              <x-icon.animate-spin
                class="size-5"
                wire:loading
                wire:target="changeOrder"
              />
              <x-icon.filter-left
                class="size-5"
                wire:loading.remove
                wire:target="changeOrder"
              />
              <span>排序依據</span>
            </button>
          </div>

          <div
            class="absolute right-0 z-10 mt-2 w-32 origin-top-right rounded-md bg-gray-50 shadow-lg ring-1 ring-black/5 focus:outline-hidden dark:bg-gray-800 dark:ring-white/20"
            x-show="orderDropdownIsOpen"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            x-on:click.outside="closeOrderDropdown"
          >
            <div class="w-full py-1">
              @foreach (CommentOrder::cases() as $commentOrder)
                <button
                  data-order="{{ $commentOrder->value }}"
                  type="button"
                  @class([
                      'flex w-full justify-start px-4 py-2',
                      'bg-gray-200 text-gray-900 outline-hidden dark:bg-gray-600 dark:text-gray-50' =>
                          $order === $commentOrder,
                      'text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' =>
                          $order !== $commentOrder,
                  ])
                  x-on:click="changeOrder"
                  wire:key="{{ $commentOrder->value }}-comment-order"
                >{{ $commentOrder->label() }}</button>
              @endforeach
            </div>
          </div>
        </div>
      </div>

      <button
        class="group relative overflow-hidden rounded-xl bg-emerald-500 px-6 py-2 [transform:translateZ(0)] before:absolute before:bottom-0 before:left-0 before:h-full before:w-full before:origin-[100%_100%] before:scale-x-0 before:bg-lividus-600 before:transition before:duration-500 before:ease-in-out hover:before:origin-[0_0] hover:before:scale-x-100 dark:bg-lividus-600 dark:before:bg-emerald-500"
        type="button"
        {{-- the comment group name should be full name --}}
        x-on:click="openCreateCommentModal"
      >
        <div class="relative z-0 flex items-center text-lg text-gray-200 transition duration-500 ease-in-out">
          <x-icon.chat-dots class="w-5" />

          @if (auth()->check())
            <span class="ml-2">新增留言</span>
          @else
            <span class="ml-2">訪客留言</span>
          @endif
        </div>
      </button>
    </div>
  </div>

  {{-- new root comment will show here --}}
  <livewire:shared.comments.comment-group
    :post-id="$postId"
    :post-user-id="$postUserId"
    :max-layer="$maxLayer"
    :comment-group-name="'root-new-comment-group'"
    :key="'root-new-comment-group-order-by-' . $order->value"
  />

  {{-- root comment list --}}
  <livewire:shared.comments.comment-list
    :post-id="$postId"
    :post-user-id="$postUserId"
    :max-layer="$maxLayer"
    :order="$order"
    :key="'root-comment-list-order-by-' . $order->value"
  />

  {{-- create comment modal --}}
  <livewire:shared.comments.create-comment-modal :post-id="$postId" />

  {{-- edit comment modal --}}
  <livewire:shared.comments.edit-comment-modal />
</div>
