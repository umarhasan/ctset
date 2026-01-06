@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">
            <i class="fas fa-paper-plane"></i> Sent Invites
        </h4>
    </div>
    <div class="card-body">

        @if($exams->count())
            @foreach($exams as $exam)
            <tr>
                <td>{{ $exam->exam_id }}</td>
                <td>{{ $exam->exam_name }}</td>
                <td>{{ $exam->testType->title }}</td>
                <td>{{ $exam->invitations_count }}</td>
                <td>
                    <a href="{{ route('exams.view-invited-students',$exam->id) }}"
                    class="btn btn-info btn-sm">
                    View
                    </a>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="5" class="text-center">No sent invites</td>
            </tr>
        @endif

    </div>
</div>
@endsection
