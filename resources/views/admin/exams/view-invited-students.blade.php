@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-warning text-dark">
        <h4 class="mb-0">
            <i class="fas fa-users"></i> Invited Students Of {{ $exam->name }}
        </h4>
        <p class="mb-0">
            <small>(Students to which invite has been sent for {{ $exam->name }})</small>
        </p>
        <p class="mb-0">
            Exam ID: {{ $exam->exam_id }} | Test Type: {{ $exam->testType->title }} |
            Date: {{ $exam->exam_date }}
        </p>
    </div>

    <div class="card-body">
        <!-- Status Filter Tabs -->
        <div class="row mb-4">
            <div class="col">
                <ul class="nav nav-pills nav-fill">
                    <li class="nav-item">
                        <a class="nav-link {{ $status == 'All' ? 'active' : '' }}"
                           href="{{ route('exams.view-invited-students', ['examId' => $exam->exam_id, 'status' => 'All']) }}">
                            All <span class="badge bg-secondary">{{ $statusCounts['All'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status == 'Unregistered' ? 'active' : '' }}"
                           href="{{ route('exams.view-invited-students', ['examId' => $exam->exam_id, 'status' => 'Unregistered']) }}">
                            Unregistered <span class="badge bg-warning">{{ $statusCounts['Unregistered'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status == 'Incompleted' ? 'active' : '' }}"
                           href="{{ route('exams.view-invited-students', ['examId' => $exam->exam_id, 'status' => 'Incompleted']) }}">
                            Incompleted <span class="badge bg-info">{{ $statusCounts['Incompleted'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status == 'Completed' ? 'active' : '' }}"
                           href="{{ route('exams.view-invited-students', ['examId' => $exam->exam_id, 'status' => 'Completed']) }}">
                            Completed <span class="badge bg-success">{{ $statusCounts['Completed'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status == 'Absent' ? 'active' : '' }}"
                           href="{{ route('exams.view-invited-students', ['examId' => $exam->exam_id, 'status' => 'Absent']) }}">
                            Absent <span class="badge bg-danger">{{ $statusCounts['Absent'] }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-secondary">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        {{-- <th>Rotation</th> --}}
                        <th>Email</th>
                        <th>Status</th>
                        <th>Sent At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <h4>Invited Students of {{ $exam->exam_name }}</h4>

                    @foreach($invitations as $invitation)
                    <tr>
                         <td>{{ $invitation->user->id }}</td>
                        <td>{{ $invitation->user->name }}</td>
                        <td>{{ $invitation->user->email }}</td>
                        <td>
                            <span class="badge bg-{{ $invitation->status_class }}">
                                {{ $invitation->status }}
                            </span>
                        </td>
                        <td>{{ optional($invitation->sent_at)->format('d-m-Y h:i A') }}</td>
                        <td>
                            <form method="POST"
                                action="{{ route('exams.update-status',$invitation->id) }}">
                                @csrf
                                <select name="status" onchange="this.form.submit()">
                                    <option {{ $invitation->status=='Unregistered'?'selected':'' }}>Unregistered</option>
                                    <option {{ $invitation->status=='Incompleted'?'selected':'' }}>Incompleted</option>
                                    <option {{ $invitation->status=='Completed'?'selected':'' }}>Completed</option>
                                    <option {{ $invitation->status=='Absent'?'selected':'' }}>Absent</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Showing {{ $invitations->firstItem() }} to {{ $invitations->lastItem() }}
                of {{ $invitations->total() }} students |
                {{ $invitations->perPage() }} rows per page
            </div>
            <div>
                <a href="{{ route('exams.sent-invites') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Sent Invites
                </a>
                <button type="button" class="btn btn-primary" onclick="exportToExcel()">
                    <i class="fas fa-download"></i> Export to Excel
                </button>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $invitations->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    function exportToExcel() {
        alert('Export functionality will be implemented here.');
        // You can implement actual export using libraries like SheetJS
    }
</script>
@endpush
@endsection
