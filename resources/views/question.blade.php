@extends('layouts.app')

@section('title', 'Question')

@section('content')
<div class="text-center">
    <h3>{{ $question['question'] }}</h3>
    <form method="POST" action="{{ url('questions/'.$step) }}">
        @csrf
        @if ($question['type'] === 'text')
            <input type="text" name="answer" class="form-control mb-3" required>
        @elseif ($question['type'] === 'number')
            <input type="number" name="answer" class="form-control mb-3" required>
        @endif
        <button type="submit" class="btn btn-primary">Next</button>
    </form>
</div>
@endsection
