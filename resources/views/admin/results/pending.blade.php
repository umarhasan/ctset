@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-warning text-dark">
        <h4 class="mb-0">
            <i class="fas fa-clock"></i> Pending Exam Results
        </h4>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-secondary">
                <tr>
                    <th>Exam ID</th>
                    <th>Test Type</th>
                    <th>Exam Name</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Duration</th>
                    <th>Day Type</th>
                    <th>Start / Days</th>
                    <th>Login / End</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            @forelse($exams as $exam)
                <tr>
                    <td>{{ $exam->exam_id }}</td>
                    <td>{{ $exam->testType->title ?? '-' }}</td>
                    <td class="text-start">{{ $exam->exam_name }}</td>

                    <td>
                        @foreach($exam->questionTypes as $qt)
                            <div>{{ $qt->title }}</div>
                            @if(!$loop->last)
                                <hr class="my-1 mx-auto" style="width:60%">
                            @endif
                        @endforeach
                    </td>

                    <td>{{ \Carbon\Carbon::parse($exam->exam_date)->format('d-m-Y') }}</td>
                    <td>{{ ($exam->hours ?? 0).'H '.($exam->minutes ?? 0).'M' }}</td>
                    <td>{{ $exam->examDuration->title ?? '-' }}</td>
                    <td>{{ $exam->exam_time ?? '-' }}</td>
                    <td>{{ $exam->long_before ?? '-' }}</td>
                    <td>{{ $exam->created_at->format('d-m-Y') }}</td>

                    {{-- ACTION COLUMN --}}
                    <td>

                        {{-- RESULT NOT CALCULATED --}}
                        @if(!$exam->result || !$exam->result->is_calculated)
                            <form action="{{ route('results.calculate',$exam->id) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                <button class="btn btn-primary btn-sm">
                                    <i class="fas fa-calculator"></i> Calculate
                                </button>
                            </form>

                        {{-- RESULT CALCULATED --}}
                        @else
                            <a href="{{ route('results.view',$exam->id) }}"
                               class="btn btn-success btn-sm">
                                <i class="fas fa-eye"></i> View Result
                            </a>

                            @if($exam->result->is_announced)
                                <div class="mt-1">
                                    <span class="badge bg-success">Announced</span>
                                </div>
                            @endif
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center text-muted">
                        No pending results found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-3">
            {{ $exams->links() }}
        </div>
    </div>
</div>
@endsection
