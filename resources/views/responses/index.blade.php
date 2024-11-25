@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4">Survey Responses</h1>

    <!-- Filter Dropdown for Question Versions -->
    <form id="versionFilterForm" method="GET" action="{{ route('responses.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <label for="version" class="form-label fw-bold">Select Question Version:</label>
                <select id="version" name="version" class="form-select" onchange="document.getElementById('versionFilterForm').submit();">
                    @foreach ($versions as $version)
                        <option value="{{ $version }}" {{ $version == $selectedVersion ? 'selected' : '' }}>
                            Version {{ $version }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <!-- Table for Survey Responses -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-primary">
                <tr>
                    <th>Response ID</th>
                    <th>Question ID</th>
                    @foreach ($questions as $question)
                        <th>
                            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#questionModal{{ $question['id'] }}">
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
</div>

    <!-- Pagination links -->
    <div class="pagination-container">
        {{ $responses->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- Modals for Questions -->
@foreach ($questions as $question)
<div class="modal fade" id="questionModal{{ $question['id'] }}" tabindex="-1" aria-labelledby="questionModalLabel{{ $question['id'] }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="questionModalLabel{{ $question['id'] }}">Question {{ $question['id'] }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ $question['question'] }}</p>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
