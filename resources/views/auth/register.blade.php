@extends('layouts.front')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">  
                <div class="card mb-4 mt-4">
                    <div class="card-header">{{ __('Register') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('register.post') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name (Username)') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label for="profile_picture" class="col-md-4 col-form-label text-md-end">{{ __('Profile Picture (Optional)') }}</label>

                                <div class="col-md-6">
                                    <input id="profile_picture" type="file" class="form-control @error('profile_picture') is-invalid @enderror" name="profile_picture" accept="image/*">
                                    <div class="form-text text-muted">Jika tidak mengunggah, Anda akan diberikan foto profil bawaan (default).</div>

                                    @error('profile_picture')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="passwordInput" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    
                                     <!-- Checklist visual OWASP agar user tahu syaratnya SEBELUM submit -->
                                    <div class="mt-2 p-2 bg-light border rounded small" id="passwordHints">
                                        <div class="fw-bold text-muted mb-1">Syarat Password:</div>
                                        <div id="hint-length" class="text-danger" data-label="Min. 8 karakter"><i class="fas fa-times-circle me-1"></i>Min. 8 karakter</div>
                                        <div id="hint-upper"  class="text-danger" data-label="Huruf besar (A-Z)"><i class="fas fa-times-circle me-1"></i>Huruf besar (A-Z)</div>
                                        <div id="hint-lower"  class="text-danger" data-label="Huruf kecil (a-z)"><i class="fas fa-times-circle me-1"></i>Huruf kecil (a-z)</div>
                                        <div id="hint-number" class="text-danger" data-label="Angka (0-9)"><i class="fas fa-times-circle me-1"></i>Angka (0-9)</div>
                                        <div id="hint-symbol" class="text-danger" data-label="Simbol (!@#$%...)"><i class="fas fa-times-circle me-1"></i>Simbol (!@#$%...)</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                    
                                    <a class="btn btn-link" href="{{ route('login') }}">
                                        {{ __('Already have an account? Login') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('passwordInput');
            if (passwordInput) {
                passwordInput.addEventListener('input', function () {
                    const val = this.value;

                    // Fungsi helper: update elemen hint jadi hijau (✓) atau merah (✗)
                    function check(id, condition) {
                        const el = document.getElementById(id);
                        if (!el) return;
                        if (condition) {
                            el.className = 'text-success';
                            el.innerHTML = '<i class="fas fa-check-circle me-1"></i>' + el.dataset.label;
                        } else {
                            el.className = 'text-danger';
                            el.innerHTML = '<i class="fas fa-times-circle me-1"></i>' + el.dataset.label;
                        }
                    }

                    check('hint-length', val.length >= 8);
                    check('hint-upper',  /[A-Z]/.test(val));
                    check('hint-lower',  /[a-z]/.test(val));
                    check('hint-number', /[0-9]/.test(val));
                    check('hint-symbol', /[^A-Za-z0-9]/.test(val));
                });
            }
        });
    </script>
@endsection
