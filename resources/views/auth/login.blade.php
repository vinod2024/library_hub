<x-guest-layout>

    <!-- Logo -->
    <div class="flex justify-center items-center mb-6">
        <img src="{{ asset('images/logo.png') }}" alt="Library Admin" class="admin-logo" width="50%">
    </div>

    <!-- Login Card -->
    <div class="shadow-lg rounded-xl p-6 border border-gray-200" style="
    background: cornsilk;">
        
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full"
                              type="email"
                              name="email"
                              :value="old('email')"
                              required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password"
                              type="password"
                              name="password"
                              class="block mt-1 w-full"
                              required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                           name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <!-- Login Button -->
            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ms-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <!-- Promotion Box -->
    <div class="mt-8 bg-indigo-50 border border-indigo-200 rounded-xl p-5 text-center text-gray-700 shadow-sm p-2" style="background: cadetblue;color: darkred;">
        <p class="text-lg font-semibold mb-1">👉 This Library App is Free!</p>
        <p class="text-sm">Connect with us to implement this app for your library.</p>        
        <hr class="my-3 border-indigo-300">
        <p class="text-sm">Looking to build a business application, website, or need secure hosting solutions?</p>        
        <p class="mt-4">
            📞 <strong>+91 79827 48725</strong><br>
            ✉️ <strong>ashok4web@gmail.com</strong>
        </p>
    </div>

    <!-- Footer -->
    <div class="mt-6 text-center">
        <p class="text-gray-500 text-sm">© 2024 Library Management System. All rights reserved.</p>
    </div>

</x-guest-layout>
