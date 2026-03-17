<x-guest-layout>

    <!-- HOME BUTTON -->
    <div style="position: absolute; top: 20px; right: 30px; z-index: 50;">
        <a href="/"
           class="text-sm font-semibold text-gray-700 hover:text-indigo-600">
            Home
        </a>
    </div>


    <!-- ============================= -->
    <!-- REGISTRATION FORM -->
    <!-- ============================= -->
    <div id="registerWrapper" class="relative">

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input id="name"
                              class="block mt-1 w-full"
                              type="text"
                              name="name"
                              :value="old('name')"
                              required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email"
                              class="block mt-1 w-full"
                              type="email"
                              name="email"
                              :value="old('email')"
                              required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Faculty -->
            <div class="mt-4">
                <x-input-label for="faculty_id" :value="__('Faculty')" />

                <select name="faculty_id"
                        id="faculty_id"
                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                        required>

                    <option value="">Select Faculty</option>

                    @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}"
                            {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                    @endforeach

                </select>

                <x-input-error :messages="$errors->get('faculty_id')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Set Password')" />
                <x-text-input id="password"
                              class="block mt-1 w-full"
                              type="password"
                              name="password"
                              required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation"
                              class="block mt-1 w-full"
                              type="password"
                              name="password_confirmation"
                              required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-primary-button class="w-full justify-center py-3 text-base">
                    Submit Registration
                </x-primary-button>
            </div>

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}"
                       class="text-indigo-600 hover:underline font-medium">
                        Login here
                    </a>
                </p>
            </div>

        </form>

    </div>

    <!-- Consent Modal: This modal is included on the registration page to ensure that users provide informed consent regarding the collection and use of their personal data before they can proceed with creating an account. -->
    @include('components.consent-modal')

    <script>

        document.body.style.overflow = "hidden";
        document.getElementById('registerWrapper').style.pointerEvents = "none";

        function toggleProceed() {

            const checkbox = document.getElementById('consentCheckbox');
            const button = document.getElementById('proceedBtn');

            if (checkbox.checked) {
                button.disabled = false;
                button.style.opacity = "1";
                button.style.cursor = "pointer";
            } else {
                button.disabled = true;
                button.style.opacity = "0.5";
                button.style.cursor = "not-allowed";
            }

        }

        function acceptConsent() {
            document.getElementById('consentOverlay').remove();
            document.body.style.overflow = "auto";
            document.getElementById('registerWrapper').style.pointerEvents = "auto";
        }

        function redirectHome() {
            window.location.href = "{{ url('/') }}";
        }

    </script>

</x-guest-layout>