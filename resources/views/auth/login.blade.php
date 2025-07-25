@extends('layout.base')
@section('title', 'Login')
@section('content')
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md md:w-full max-w-md">
            <div class="flex justify-center mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-cpu-icon lucide-cpu">
                    <path d="M12 20v2" />
                    <path d="M12 2v2" />
                    <path d="M17 20v2" />
                    <path d="M17 2v2" />
                    <path d="M2 12h2" />
                    <path d="M2 17h2" />
                    <path d="M2 7h2" />
                    <path d="M20 12h2" />
                    <path d="M20 17h2" />
                    <path d="M20 7h2" />
                    <path d="M7 20v2" />
                    <path d="M7 2v2" />
                    <rect x="4" y="4" width="16" height="16" rx="2" />
                    <rect x="8" y="8" width="8" height="8" rx="1" />
                </svg>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        autocomplete="email" autofocus
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" required autocomplete="current-password"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                        <button type="button" onclick="show(this)"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" id="eyeIcon" class="h-5 w-5" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                <path fill-rule="evenodd"
                                    d="M1.32 11.45C2.81 6.98 7.03 3.75 12 3.75c4.97 0 9.19 3.22 10.68 7.69.12.36.12.75 0 1.11C21.19 17.02 16.97 20.25 12 20.25c-4.97 0-9.19-3.22-10.68-7.69a1.76 1.76 0 0 1 0-1.11ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>



                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                            class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember Me
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a class="inline-block align-baseline text-sm text-blue-500 hover:text-blue-800"
                            href="{{ route('password.request') }}">
                            Forgot Your Password?
                        </a>
                    @endif
                </div>

                <div class="flex items-center">
                    <button type="submit"
                        class="bg-gray-600 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded-2xl focus:outline-none focus:shadow-outline w-25">
                        Login
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection
