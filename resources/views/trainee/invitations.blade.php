@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0"><i class="fas fa-list-alt"></i> My Exam Invitations</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Exam ID</th>
                        <th>Test Type</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Time Duration</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invitations as $invitation)
                    <tr>
                        <td>{{ $invitation->exam->exam_id }}</td>
                        <td>{{ $invitation->exam->testType->title }}</td>
                        <td>{{ $invitation->exam->exam_name }}</td>
                        <td>{{ $invitation->exam->examDuration->title ?? '-' }}</td>
                        <td>{{ $invitation->exam->exam_date }}</td>
                        <td>{{ ($invitation->exam->hours ?? 0).'h '.($invitation->exam->minutes ?? 0).'m' }}</td>
                        <td>
                            <span class="badge bg-{{ $invitation->status_class }}">
                                {{ $invitation->status_text }}
                            </span>
                        </td>
                        <td>
                            @if($invitation->status == 'Unregistered')
                                <a href="{{ route('trainee.take-exam', $invitation->id) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-play"></i> Take Exam
                                </a>
                            @elseif($invitation->status == 'Completed')
                                <button class="btn btn-sm btn-success" disabled>
                                    <i class="fas fa-check"></i> Completed
                                </button>
                            @elseif($invitation->status == 'Incompleted')
                                <a href="{{ route('trainee.take-exam', $invitation->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-redo"></i> Continue
                                </a>
                            @else
                                <button class="btn btn-sm btn-secondary" disabled>
                                    <i class="fas fa-times"></i> {{ $invitation->status }}
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No exam invitations found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $invitations->links() }}
        </div>
    </div>
</div>
@endsection
