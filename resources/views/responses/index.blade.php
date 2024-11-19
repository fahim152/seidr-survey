@extends('layouts.app')

@section('title', 'Responses')

@section('content')
<h2 class="h5-base">Survey Responses</h2>

<div class="alert-block">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Responses</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($responses as $response)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @foreach (json_decode($response->answers, true) as $questionId => $answer)
                        <p><strong>Question {{ $questionId }}:</strong> {{ $answer }}</p>
                    @endforeach
                </td>
                <td>{{ $response->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
