@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0">
            <i class="fas fa-chart-bar"></i>
            Result – {{ $exam->exam_name }}
        </h4>
    </div>

    <div class="card-body">

        {{-- SUMMARY --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="alert alert-primary text-center">
                    <strong>Total Students</strong><br>
                    {{ $result->total_students }}
                </div>
            </div>

            <div class="col-md-3">
                <div class="alert alert-success text-center">
                    <strong>Passed</strong><br>
                    {{ $result->passed_students }}
                </div>
            </div>

            <div class="col-md-3">
                <div class="alert alert-danger text-center">
                    <strong>Failed</strong><br>
                    {{ $result->failed_students }}
                </div>
            </div>

            <div class="col-md-3">
                <div class="alert alert-info text-center">
                    <strong>Highest Marks</strong><br>
                    {{ $result->highest_marks }}
                </div>
            </div>
        </div>

        {{-- ACTION --}}
        @if(!$result->is_announced)
        <form action="{{ route('results.announce',$exam->id) }}"
              method="POST" class="mb-3">
            @csrf
            <button class="btn btn-warning">
                <i class="fas fa-bullhorn"></i> Announce Result
            </button>
        </form>
        @else
            <div class="alert alert-success">
                Result Announced on
                {{ $result->announced_at }}
            </div>
        @endif

        {{-- STUDENT LIST --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-secondary">
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Email</th>
                        <th>Marks</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($attempts as $attempt)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $attempt->user->name }}</td>
                        <td>{{ $attempt->user->email }}</td>
                        <td>{{ $attempt->obtained_marks }}</td>
                        <td>
                            @if($attempt->obtained_marks >= 50)
                                <span class="badge bg-success">Pass</span>
                            @else
                                <span class="badge bg-danger">Fail</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $attempts->links() }}
        </div>
    </div>
</div>
@endsection
