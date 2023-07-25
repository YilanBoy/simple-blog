@section('title', '忘記密碼')

<div class="container mx-auto">
  <div class="flex items-center justify-center px-4 xl:px-0">

    <div class="flex w-full flex-col items-center justify-center">
      {{-- 頁面標題 --}}
      <div class="fill-current text-2xl text-gray-700 dark:text-gray-50">
        <i class="bi bi-question-circle"></i><span class="ml-4">忘記密碼</span>
      </div>

      <x-card class="mt-4 w-full space-y-6 overflow-hidden sm:max-w-md">
        <div class="text-gray-600 dark:text-gray-50">
          {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        {{-- Session 狀態訊息 --}}
        <x-auth-session-status :status="session('status')" />

        {{-- 驗證錯誤訊息 --}}
        <x-auth-validation-errors :errors="$errors" />

        <form wire:submit.prevent="store">

          {{-- 信箱 --}}
          <div>
            <x-floating-label-input
              name="email"
              type="text"
              :id="'email'"
              :placeholder="'電子信箱'"
              required
              autofocus
              wire:model.defer="email"
            />
          </div>

          <div class="mt-6 flex items-center justify-end">
            <x-button>
              {{ __('Email Password Reset Link') }}
            </x-button>
          </div>
        </form>
      </x-card>
    </div>

  </div>
</div>
