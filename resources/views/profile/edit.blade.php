<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Two-Factor Authentication</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Require a one-time code (sent to your email) at sign-in for extra security.
                        </p>
                    </header>
                    <form method="POST" action="{{ route('2fa.toggle') }}" class="mt-6">
                        @csrf
                        <p class="mb-3 text-sm">
                            Status:
                            @if(auth()->user()->two_factor_enabled)
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded">Enabled</span>
                            @else
                                <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded">Disabled</span>
                            @endif
                        </p>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            {{ auth()->user()->two_factor_enabled ? 'Disable 2FA' : 'Enable 2FA' }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
