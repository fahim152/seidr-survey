@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Survey Responses</h1>

    <!-- Filter Dropdown for Question Versions -->
    <form id="versionFilterForm" method="GET" action="{{ route('responses.index') }}">
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <label for="version" class="form-label">Select Question Version:</label>
            <select id="version" name="version" class="form-select" onchange="document.getElementById('versionFilterForm').submit();">
                @foreach ($versions as $version)
                    <option value="{{ $version }}" {{ $version == $selectedVersion ? 'selected' : '' }}>
                        Version {{ $version }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <!-- Table for Survey Responses -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Response ID</th>
                <th>Question ID</th>
                @foreach ($questions as $question)
                    <th>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#questionModal{{ $question['id'] }}">
                            Question {{ $question['id'] }}
                        </a>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($responses as $response)
                <tr>
                    <td>{{ $response->id }}</td>
                    <td>{{ $response->question_id }}</td>
                    @foreach ($questions as $question)
                        <td>{{ $response->answers[$question['id']] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination links -->
    <div class="d-flex justify-content-center">
        {{ $responses->links() }}
    </div>
</div>

<!-- Modals for Questions -->
@foreach ($questions as $question)
<div class="modal fade" id="questionModal{{ $question['id'] }}" tabindex="-1" aria-labelledby="questionModalLabel{{ $question['id'] }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="questionModalLabel{{ $question['id'] }}">Question {{ $question['id'] }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $question['question'] }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
