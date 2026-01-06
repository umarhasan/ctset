@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-info text-white">
        Result Detail
    </div>

    <div class="card-body">
        <p><b>Exam:</b> {{ $exam->exam_name }}</p>
        <p><b>Your Marks:</b> {{ $attempt->obtained_marks }}</p>
        <p><b>Status:</b>
            @if($attempt->obtained_marks >= 50)
                <span class="text-success">Passed</span>
            @else
                <span class="text-danger">Failed</span>
            @endif
        </p>
    </div>
</div>
@endsection
