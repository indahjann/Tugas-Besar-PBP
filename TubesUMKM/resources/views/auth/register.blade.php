@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <h2 class="text-2xl font-semibold mb-6">Register</h2>
    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" class="input-base" name="name" required>
            @error('name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <input type="text" class="input-base" name="username" required>
            @error('username') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" class="input-base" name="email" required>
            @error('email') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" class="input-base" name="password" required>
            @error('password') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input type="password" class="input-base" name="password_confirmation" required>
        </div>
        <button type="submit" class="button-primary">Register</button>
    </form>
</div>
@endsection
