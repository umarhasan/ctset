@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">
            <i class="fas fa-paper-plane"></i> Sent Invites
        </h4>
    </div>
    <div class="card-body">

        @if($exams->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-secondary">
                    <tr>
                        <th>ID</th>
                        <th>Exam Name</th>
                        <th>Test Type</th>
                        <th>Total Invites</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exams as $exam)
                    <tr>
                        <td>{{ $exam->exam_id }}</td>
                        <td>{{ $exam->exam_name }}</td>
                        <td>{{ $exam->testType->title }}</td>
                        <td>{{ $exam->total_invites ?? 0 }}</td>
                        <td>
                            <a href="{{ route('exams.view-invited-students', $exam->exam_id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> View Invited
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $exams->links() }}
        </div>
        @else
        <div class="alert alert-warning text-center">
            No exams with sent invites found.
        </div>
        @endif

    </div>
</div>
@endsection
