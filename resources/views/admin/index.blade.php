@extends('admin.parent')

@section('content')
<div class="card">
        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}" alt="Profile" class="rounded-circle w-25">
            <h2 class="mt-2">{{ auth()->user()->name }}</h2>
            <h3>Web Designer</h3>
            <div class="social-links mt-2">
                <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
            </div>
        </div>
    </div>
@endsection