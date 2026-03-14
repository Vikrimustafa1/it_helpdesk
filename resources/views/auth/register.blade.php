@extends('layouts.app')

@section('page-title', 'Register')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow-lg border-0" style="min-width: 360px; max-width: 520px; width: 100%; backdrop-filter: blur(18px); background: rgba(255,255,255,0.92);">
            <div class="card-body p-4">
                <h5 class="card-title mb-3 text-center">Daftar Akun Baru</h5>

                <p class="text-muted small text-center mb-3">
                    Lengkapi data di bawah untuk membuat akun IT Helpdesk.
                </p>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="unit" class="form-label">Unit / Ruangan</label>
                            <input type="text" name="unit" id="unit"
                                   class="form-control @error('unit') is-invalid @enderror"
                                   value="{{ old('unit') }}">
                            @error('unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">No. Telepon</label>
                            <input type="text" name="phone" id="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}">
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Peran</label>
                        <select name="role" id="role"
                                class="form-select @error('role') is-invalid @enderror" required>
                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User (Pelapor)</option>
                            <option value="teknisi_hardware" {{ old('role') === 'teknisi_hardware' ? 'selected' : '' }}>Teknisi Hardware</option>
                            <option value="teknisi_software" {{ old('role') === 'teknisi_software' ? 'selected' : '' }}>Teknisi Software</option>
                        </select>
                        @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control" required>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary rounded-pill">
                            <i class="bi bi-person-plus me-1"></i> Daftar
                        </button>
                    </div>

                    <div class="text-center">
                        <small class="text-muted">
                            Sudah punya akun?
                            <a href="{{ route('login') }}">Login di sini</a>
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

