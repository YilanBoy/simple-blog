@props(['model'])

@script
  <script>
    Alpine.data('tagify', () => ({
      tagsListUrl: @js(route('api.tags')),
      tags: @entangle($model).live,

      init() {
        let tagsInput = document.querySelector('#tags');

        // because the editor will be cached by livewire in navigation
        fetch(this.tagsListUrl)
          .then((response) => response.json())
          .then(function(tagsJson) {
            // we can't use the 'this' keyword here
            // so this.$refs will not work
            return new Tagify(tagsInput, {
              whitelist: tagsJson.data,
              enforceWhitelist: true,
              maxTags: 5,
              dropdown: {
                // show the dropdown immediately on focus
                enabled: 0,
                maxItems: 5,
                // place the dropdown near the typed text
                position: 'text',
                // keep the dropdown open after selecting a suggestion
                closeOnSelect: false,
                highlightFirst: true
              },
              callbacks: {
                // binding the value of the tag input to the livewire attribute 'tags'
                'change': (event) => this.tags = event.detail.value
              }
            });
          })
          .then(function(tagify) {
            try {
              tagify.addTags(JSON.parse(this.tags));
            } catch (e) {
              // forget about it :)
            }

            document.addEventListener('livewire:navigating', () => {
              if (tagify !== null) {
                console.log('destroy tagify before navigating away');
                tagify.destroy();
                tagify = null;
              }
            });
          });
      }
    }));
  </script>
@endscript

<div
  class="col-span-2"
  x-data="tagify"
  wire:ignore
>
  {{-- custom tagify style --}}
  <style>
    .tagify {
      align-items: center;
    }

    .tagify__tag {
      height: 2rem;
    }

    .dark .tagify__tag {
      --tag-text-color--edit: #f9fafb;
    }
  </style>

  <label
    class="hidden"
    for="tags"
  >標籤 (最多 5 個)</label>

  <input
    class="w-full rounded-md bg-white dark:bg-gray-700"
    id="tags"
    type="text"
    placeholder="標籤 (最多 5 個)"
  >
</div>
