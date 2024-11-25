@extends('layouts.app')

@section('title', 'Survey')

@section('content')

<div id="survey-app">
    <!-- Container for dynamic question content -->
    <div id="question-container" class="form-container">
        <!-- Questions will be dynamically injected here -->
         
    </div>

    <!-- Navigation Buttons -->
    <div class="button-container">
        <button id="prev-btn" onclick="prevQuestion()" class="previous-button" style="display: none;">Previous</button>
        <button id="next-btn" onclick="nextQuestion()" class="next-button">Next</button>
        <button id="submit-btn" onclick="submitSurvey()" class="next-button" style="display: none;">Submit</button>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/survey.js') }}"></script>
@endsection
