@extends('layouts.front')

@section('title', 'Edit Profile')

@section('content')

<div class="row justify-content-center">

    <div class="col-md-8">

        <div class="card shadow">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit Profile</h4>
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('customer.profile.update') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    <div class="text-center mb-4">

                        @if($user->profile_picture)

                            <img src="{{ asset('storage/'.$user->profile_picture) }}"
                                 class="rounded-circle shadow"
                                 width="150"
                                 height="150"
                                 style="object-fit:cover;">

                        @else

                            <img src="https://via.placeholder.com/150"
                                 class="rounded-circle shadow">

                        @endif

                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Profil</label>
                        <input type="file"
                               name="profile_picture"
                               class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name',$user->name) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email',$user->email) }}">
                    </div>

                    <hr>

                    <h5>Ganti Password (Opsional)</h5>

                    <div class="mb-3">
                        <label>Password Baru</label>
                        <input type="password"
                               name="password"
                               class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Konfirmasi Password</label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control">
                    </div>

                    <button class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection