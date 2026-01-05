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
                        <td>{{ $exam->exam_id ?? '-' }}</td>
                        <td>{{ $exam->testType->title ?? '-' }}</td>
                        <td class="text-start">{{ $exam->exam_name }}</td>
                        <td>{{ $exam->questionType->title ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($exam->exam_date)->format('d-m-Y') }}</td>
                        <td>{{ ($exam->hours ?? 0).'h '.($row->minutes ?? 0).'m' }}</td>
                        <td>{{ $exam->examDuration->title ?? '-' }}</td>
                        <td>{{ $exam->exam_time ?? '-' }}</td>
                        <td>{{ $exam->long_before ?? '-' }}</td>
                        <td>{{ $exam->created_at->format('d-m-Y') }}</td>
                        <td>
                            <span class="badge bg-warning">{{ $exam->unregistered_count ?? 0 }} pending</span>
                        </td>
                        <td>
                            <a href="{{ route('exams.send-invite', $exam->exam_id) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-paper-plane"></i> Send Invite
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center">No pending exams found.</td>
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
<script>
    $(function () {
        $('#examTable').DataTable({
            scrollX: true,
            autoWidth: true,
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            lengthChange: true,

            dom:
                "<'row mb-2'<'col-md-6'l><'col-md-6 text-end'f>>" +
                "<'row'<'col-md-12'tr>>" +
                "<'row mt-2'<'col-md-5'i><'col-md-7 text-end'p>>",

            columnDefs: [
                { targets: -1, orderable: false }
            ],

            language: {
                search: "",
                searchPlaceholder: "Search exams..."
            }
        });

            $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
