<x-guest-layout>

    <!-- HOME BUTTON (Top Right Corner) -->
    <div style="position: absolute; top: 20px; right: 30px;">
        <a href="/"
           class="text-sm font-semibold text-gray-700 hover:text-indigo-600">
            Home
        </a>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- PASSWORD RESET SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg border border-green-300 bg-green-50 text-green-800 flex items-center gap-2 shadow-sm whitespace-nowrap">
            
            <!-- Icon -->
            {{-- <svg xmlns="http://www.w3.org/2000/svg" 
                 class="w-5 h-5 text-green-600 flex-shrink-0" 
                 fill="none" 
                 viewBox="0 0 24 24" 
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M5 13l4 4L19 7" />
            </svg> --}}

            <!-- Message -->
            <span class="text-sm font-medium">
                {{ session('success') }}
            </span>

        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />

            <x-text-input id="email"
                          class="block mt-1 w-full"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required
                          autofocus
                          autocomplete="username" />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">

            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password"
                          class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required
                          autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />

        </div>

        <!-- Remember Me -->
        <div class="mt-4 flex items-center justify-between">

            <label for="remember_me" class="inline-flex items-center">

                <input id="remember_me"
                       type="checkbox"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                       name="remember">

                <span class="ms-2 text-sm text-gray-600">
                    {{ __('Remember me') }}
                </span>

            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-600 hover:underline"
                   href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif

        </div>

        <!-- Login Button -->
        <div class="mt-6">

            <x-primary-button class="w-full justify-center py-3 text-base">
                {{ __('Log in') }}
            </x-primary-button>

        </div>

        <!-- Register Link -->
        <div class="mt-4 text-center">

            <p class="text-sm text-gray-600">
                Don't have an account yet?

                <a href="{{ route('register') }}"
                   class="text-indigo-600 hover:underline font-medium">
                    Register here
                </a>
            </p>

        </div>

    </form>

</x-guest-layout>