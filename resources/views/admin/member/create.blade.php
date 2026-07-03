@extends('layouts.admin')

@section('title', 'Daftarkan Karyawan Baru')

@section('content')
<h1 class="mt-4">Registrasi Akun Baru</h1>
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-dark text-white"><i class="fas fa-user-plus me-1"></i> Form Tambah User</div>
    <div class="card-body">
        
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" autocomplete="off" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="fw-bold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="fw-bold text-danger">Jabatan (Role) <i class="fas fa-exclamation-triangle"></i></label>
                <select name="role" class="form-select border-danger @error('role') is-invalid @enderror" required>
                    <option value="pegawai" selected>Staff / Pegawai</option>
                    <option value="administrator">Administrator</option>
                    <option value="customer">Customer</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" id="passwordInput" class="form-control @error('password') is-invalid @enderror" placeholder="Buat password kuat..." required>
                    @error('password')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                    <!-- Checklist visual OWASP agar user tahu syaratnya SEBELUM submit -->
                    <div class="mt-2 p-2 bg-light border rounded small" id="passwordHints">
                        <div class="fw-bold text-muted mb-1">Syarat Password (OWASP):</div>
                        <div id="hint-length" class="text-danger" data-label="Min. 8 karakter"><i class="fas fa-times-circle me-1"></i>Min. 8 karakter</div>
                        <div id="hint-upper"  class="text-danger" data-label="Huruf besar (A-Z)"><i class="fas fa-times-circle me-1"></i>Huruf besar (A-Z)</div>
                        <div id="hint-lower"  class="text-danger" data-label="Huruf kecil (a-z)"><i class="fas fa-times-circle me-1"></i>Huruf kecil (a-z)</div>
                        <div id="hint-number" class="text-danger" data-label="Angka (0-9)"><i class="fas fa-times-circle me-1"></i>Angka (0-9)</div>
                        <div id="hint-symbol" class="text-danger" data-label="Simbol (!@#$%...)"><i class="fas fa-times-circle me-1"></i>Simbol (!@#$%...)</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Konfirmasi Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ketik ulang password..." required>
                    @error('password_confirmation')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-dark fw-bold px-4 mt-3"><i class="fas fa-id-card"></i> Tambah user</button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary mt-3 ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
</script>
@endpush