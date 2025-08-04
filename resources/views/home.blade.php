@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @auth
                        {{ __('Dashboard') }}
                    @else
                        {{ __('Please Login') }}
                    @endauth
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @auth
                        {{ __('You are logged in!') }}
                    @else
                        {{ __('Please login to access your dashboard.') }}
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
