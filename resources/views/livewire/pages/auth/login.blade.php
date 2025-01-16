<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Enums\UserStatusEnum;

new #[Layout('layouts.app')] class extends Component {
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        $user = Auth::user();

        if ($user->hasRole(App\Enums\UserRole::PASUKAN->value)) {
            if ($user->status != UserStatusEnum::ACTIVE->value) {
                Auth::logout();

                session()->flash('status', 'Akun Anda belum diaktivasi. Silakan hubungi admin untuk aktivasi.');

                return;
            }
        }
        Session::regenerate();

        if ($user->hasRole(App\Enums\UserRole::ADMIN->value)) {
            $this->redirect(route('filament.admin.pages.dashboard'), navigate: false);
        } elseif ($user->hasRole(App\Enums\UserRole::PENGIKLAN->value)) {
            $this->redirect(route('filament.pengiklan.pages.dashboard'), navigate: false);
        } elseif ($user->hasRole(App\Enums\UserRole::PASUKAN->value)) {
            $this->redirect(route('dashboard'), navigate: false);
        }
    }
}; ?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-12 px-4 sm:px-6 lg:px-8 -mb-24">
    <div class="max-w-2xl mx-auto">
        <div class="bg-gray rounded-xl overflow-hidden">
            <div class="p-8">
                <!-- Logo and Title -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Selamat Datang Kembali</h2>
                    <p class="text-gray-600 mt-2">Masuk ke akun Anda untuk melanjutkan</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form wire:submit="login" class="space-y-6">
                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input wire:model="form.email" id="email" type="email" required
                                class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="nama@email.com">
                        </div>
                        @error('form.email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input wire:model="form.password" id="password" type="password" required
                                class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Masukkan kata sandi">
                        </div>
                        @error('form.password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input wire:model="form.remember" id="remember" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Ingat Saya
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" wire:navigate
                                class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                Lupa kata sandi?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                            class="w-full flex justify-center items-center px-4 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Masuk Sekarang
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-600">
                            Belum punya akun?
                            <a href="{{ route('register') }}" wire:navigate
                                class="font-medium text-blue-600 hover:text-blue-500">
                                Daftar sekarang
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
