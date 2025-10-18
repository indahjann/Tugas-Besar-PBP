<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('layouts.navigation')

    <div class="profile-container">
        <h1 class="page-title">Pengaturan Profile</h1>

        <div class="profile-grid">
            <!-- Left Column -->
            <div class="profile-column">
                <!-- Profile Information -->
                <div class="profile-card">
                    <h2>Informasi Profile</h2>

                    @if(session('profile-updated'))
                        <div class="alert alert-success">{{ session('profile-updated') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" class="profile-form">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $user->name) }}" 
                                required 
                                class="form-control"
                            >
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input 
                                type="text" 
                                id="username" 
                                value="{{ $user->username }}" 
                                disabled 
                                class="form-control"
                            >
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $user->email) }}" 
                                required 
                                class="form-control"
                            >
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>

                <!-- Delete Account -->
                <div class="profile-card card-danger">
                    <h2>Hapus Akun</h2>
                    <p class="warning-text">Setelah akun dihapus, semua data akan hilang secara permanen.</p>

                    <form action="{{ route('profile.destroy') }}" method="POST" class="profile-form" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun?');">
                        @csrf
                        @method('DELETE')

                        <div class="form-group">
                            <label for="delete_password">Konfirmasi Password</label>
                            <input 
                                type="password" 
                                id="delete_password" 
                                name="password" 
                                required 
                                class="form-control"
                                placeholder="Masukkan password Anda"
                            >
                            @error('password', 'userDeletion')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-danger">Hapus Akun</button>
                    </form>
                </div>
            </div>

            <!-- Right Column -->
            <div class="profile-column">
                <!-- Change Password -->
                <div class="profile-card">
                    <h2>Ubah Password</h2>

                    @if(session('password-updated'))
                        <div class="alert alert-success">{{ session('password-updated') }}</div>
                    @endif

                    <form action="{{ route('password.update') }}" method="POST" class="profile-form">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="current_password">Password Saat Ini</label>
                            <input 
                                type="password" 
                                id="current_password" 
                                name="current_password" 
                                required 
                                class="form-control"
                            >
                            @error('current_password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required 
                                class="form-control"
                            >
                            @error('password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                required 
                                class="form-control"
                            >
                        </div>

                        <button type="submit" class="btn btn-primary">Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')
</body>
</html>
