@extends('layouts.app')

@section('title', 'Thank You')

@section('content')
<div class="text-center">
    <h1>Thank You!</h1>
    <p>Your responses have been recorded.</p>
    <a href="{{ url('/') }}" class="btn btn-primary mt-3">Go Back Home</a>
</div>
@endsection
