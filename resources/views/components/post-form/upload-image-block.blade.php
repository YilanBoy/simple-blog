@props([
    'imageModelName' => 'image',
    'image' => null,
    'showUploadedImage' => false,
    'previewUrlModelName' => 'previewUrl',
    'previewUrl' => null,
])

@script
  <script>
    Alpine.data('uploadImageBlock', () => ({
      imageModelName: @js($imageModelName),
      previewUrlModelName: @js($previewUrlModelName),
      isUploading: false,
      progress: 0,
      makeIsUploadingTrue() {
        this.isUploading = true;
      },
      makeIsUploadingFalse() {
        this.isUploading = false;
      },
      updateProgress(event) {
        this.progress = event.detail.progress;
      },
      changeBlockStyleWhenDragEnter() {
        this.$refs.uploadBlock.classList.remove('text-emerald-500', 'dark:text-indigo-400', 'border-emerald-500',
          'dark:border-indigo-400')
        this.$refs.uploadBlock.classList.add('text-emerald-600', 'dark:text-indigo-300', 'border-emerald-600',
          'dark:border-indigo-300')
      },
      changeBlockStyleWhenDragLeaveAndDrop() {
        this.$refs.uploadBlock.classList.add('text-emerald-500', 'dark:text-indigo-400', 'border-emerald-500',
          'dark:border-indigo-400')
        this.$refs.uploadBlock.classList.remove('text-emerald-600', 'dark:text-indigo-300', 'border-emerald-600',
          'dark:border-indigo-300')
      },
      clearImage() {
        this.$wire.set(this.imageModelName, null);
      },
      removePreviewUrl() {
        if (confirm('你確定要刪除預覽圖嗎？')) {
          this.$wire.set(this.previewUrlModelName, '');
        }
      }
    }));
  </script>
@endscript

<div
  class="col-span-2 text-base"
  x-data="uploadImageBlock"
  x-on:livewire-upload-start="makeIsUploadingTrue"
  x-on:livewire-upload-finish="makeIsUploadingFalse"
  x-on:livewire-upload-error="makeIsUploadingFalse"
  x-on:livewire-upload-progress="updateProgress"
>
  @if ($previewUrl && is_null($image))
    {{-- preview image preview --}}
    <div class="relative w-full">
      <img
        class="rounded-lg"
        id="preview-image"
        src="{{ $previewUrl }}"
        alt="preview image"
      >

      <button
        class="group absolute right-0 top-0 flex h-full w-full items-center justify-center rounded-lg transition-all duration-150 hover:bg-gray-600/50 hover:backdrop-blur-xs"
        type="button"
        x-on:click="removePreviewUrl"
      >
        <x-icon.x-circle
          class="size-24 opacity-0 transition-all duration-150 group-hover:text-gray-50 group-hover:opacity-100"
        />
      </button>

      <span
        class="dark:bg-lividus-50 absolute right-2 top-2 inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-sm font-medium text-emerald-700 ring-1 ring-inset ring-emerald-700/10 dark:text-lividus-700 dark:ring-lividus-700/10"
      >預覽圖</span>
    </div>
  @elseif ($showUploadedImage)
    {{-- uploaded image preview --}}
    <div class="relative w-full">
      <img
        class="rounded-lg"
        id="upload-image"
        src="{{ $image->temporaryUrl() }}"
        alt="preview image"
      >

      <button
        class="group absolute right-0 top-0 flex h-full w-full items-center justify-center rounded-lg transition-all duration-150 hover:bg-gray-600/50 hover:backdrop-blur-xs"
        type="button"
        x-on:click="clearImage"
      >
        <x-icon.x-circle
          class="size-24 opacity-0 transition-all duration-150 group-hover:text-gray-50 group-hover:opacity-100"
        />
      </button>

      <span
        class="dark:bg-lividus-50 absolute right-2 top-2 inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-sm font-medium text-emerald-700 ring-1 ring-inset ring-emerald-700/10 dark:text-lividus-700 dark:ring-lividus-700/10"
      >預覽圖</span>
    </div>
  @else
    {{-- Upload Area --}}
    <div
      class="relative flex cursor-pointer flex-col items-center rounded-lg border-2 border-dashed border-emerald-500 bg-transparent px-4 py-6 tracking-wide text-emerald-500 transition-all duration-300 hover:border-emerald-600 hover:text-emerald-600 dark:border-indigo-400 dark:text-indigo-400 dark:hover:border-indigo-300 dark:hover:text-indigo-300"
      x-ref="uploadBlock"
    >
      <input
        class="absolute inset-0 z-50 m-0 h-full w-full cursor-pointer p-0 opacity-0 outline-hidden"
        type="file"
        wire:model.live="{{ $imageModelName }}"
        x-on:dragenter="changeBlockStyleWhenDragEnter"
        x-on:dragleave="changeBlockStyleWhenDragLeaveAndDrop"
        x-on:drop="changeBlockStyleWhenDragLeaveAndDrop"
      >

      <div class="flex flex-col items-center justify-center space-y-2 text-center">
        <x-icon.upload class="size-10" />

        <p>預覽圖 (jpg, jpeg or png)</p>
      </div>
    </div>

    {{-- Progress Bar --}}
    <div
      class="relative mt-4 pt-1"
      x-cloak
      x-show="isUploading"
    >
      <div class="mb-4 flex h-4 overflow-hidden rounded-sm bg-emerald-200 text-xs dark:bg-indigo-200">
        <div
          class="flex flex-col justify-center whitespace-nowrap bg-emerald-500 text-center text-white dark:bg-indigo-500"
          x-bind:style="`width:${progress}%`"
        >
        </div>
      </div>
    </div>
  @endif
</div>
