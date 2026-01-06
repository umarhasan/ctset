@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-clock"></i> Pending Exam For Invitation</h4>
    </div>
    <div class="card-body table-responsive">
            <table id="examTable"
                   class="table table-bordered table-hover table-striped mb-0 text-center align-middle w-100">
                <thead class="table-light text-nowrap">
                    <tr>
                    <th width="80">Exam #</th>
                    <th width="120">Test Type</th>
                    <th width="180">Exam Name</th>
                    <th width="120">Category</th>
                    <th width="110">Date</th>
                    <th width="130">Duration</th>
                    <th width="160">Day Type</th>
                    <th width="180">Start / Days</th>
                    <th width="200">Login / End</th>
                    <th width="130">Created</th>
                    <th>Pending Invites</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse($exams as $exam)
                        <tr>
                            <td>{{ $exam->exam_id }}</td>
                            <td>{{ $exam->testType->title ?? '-' }}</td>
                            <td>{{ $exam->exam_name }}</td>

                            <td>
                                @foreach($exam->questionTypes as $qt)
                                    <div>{{ $qt->title }}</div>
                                @endforeach
                            </td>

                           <td>{{ $exam->exam_date }}</td>
                            <td>{{ ($exam->hours ?? 0).'h '.($exam->minutes ?? 0).'m' }}</td>
                            <td>{{ $exam->examDuration->title ?? '-' }}</td>
                            <td>{{ $exam->exam_time ?? '-' }}</td>
                            <td>{{ $exam->long_before ?? '-' }}</td>
                            <td>{{ $exam->created_at->format('d-m-Y') }}</td>

                            <td>
                                <span class="badge bg-warning">
                                    {{ $exam->unregistered_count }} Pending
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('exams.send-invite',$exam->id) }}"
                                class="btn btn-success btn-sm">
                                Send Invite
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center">No pending exams</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $exams->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')

@endpush
