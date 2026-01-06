@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        My Exams
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Exam</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            @foreach($exams as $exam)
                @php
                    $attempt = $exam->attempts
                        ->where('user_id',auth()->id())
                        ->first();
                @endphp
                <tr>
                    <td>{{ $exam->exam_name }}</td>
                    <td>{{ $exam->exam_date }}</td>
                    <td>{{ $attempt->status ?? 'Not Started' }}</td>
                    <td>
                        @if(!$attempt)
                            <form method="POST"
                                  action="{{ route('trainee.exam.start',$exam->id) }}">
                                @csrf
                                <button class="btn btn-primary btn-sm">
                                    Start
                                </button>
                            </form>
                        @elseif($attempt->status=='Started')
                            <form method="POST"
                                  action="{{ route('trainee.exam.submit',$exam->id) }}">
                                @csrf
                                <input type="hidden" name="marks" value="75">
                                <button class="btn btn-success btn-sm">
                                    Submit
                                </button>
                            </form>
                        @else
                            <span class="badge bg-success">Completed</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $exams->links() }}
    </div>
</div>
@endsection
