@extends('layouts.app')
<style>
/* No jumping */
table.dataTable {
    table-layout: fixed;
}

/* Equal height */
.table th, .table td {
    vertical-align: middle !important;
    white-space: nowrap;
}

/* Header strong */
.table thead th {
    font-weight: 600;
    text-transform: capitalize;
}

/* Search input */
.dataTables_filter input {
    border-radius: 20px;
    padding: 4px 12px;
}

/* Length dropdown */
.dataTables_length select {
    border-radius: 20px;
}

/* Small buttons */
.btn-xs {
    padding: 3px 6px;
    font-size: 12px;
}
</style>

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title mb-0">Exams</h3>
        <div class="ms-auto">
            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                <i class="fa fa-plus"></i> Add Exam
            </button>
        </div>
    </div>

    <div class="card-body table-responsive">
            <table id="examTable"
                   class="table table-bordered table-hover table-striped mb-0 text-center align-middle w-100">
                <thead class="table-light text-nowrap">
                <tr>
                    <th width="80">ID</th>
                    <th width="120">Test Type</th>
                    <th width="180">Exam Name</th>
                    <th width="120">Category</th>
                    <th width="110">Date</th>
                    <th width="130">Duration</th>
                    <th width="160">Day Type</th>
                    <th width="180">Start / Days</th>
                    <th width="200">Login / End</th>
                    <th width="130">Created</th>
                    <th width="90">Action</th>
                </tr>
                </thead>

                <tbody>
                @foreach($records as $row)
                    <tr>
                        <td>{{ $row->exam_id ?? '-' }}</td>
                        <td>{{ $row->testType->title ?? '-' }}</td>
                        <td class="text-start">{{ $row->exam_name }}</td>
                        <td>{{ $row->questionType->title ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->exam_date)->format('d-m-Y') }}</td>
                        <td>{{ ($row->hours ?? 0).'h '.($row->minutes ?? 0).'m' }}</td>
                        <td>{{ $row->examDuration->title ?? '-' }}</td>
                        <td>{{ $row->exam_time ?? '-' }}</td>
                        <td>{{ $row->long_before ?? '-' }}</td>
                        <td>{{ $row->created_at->format('d-m-Y') }}</td>
                        <td class="text-nowrap">
                            <button class="btn btn-warning btn-xs me-1"
                                    data-bs-toggle="tooltip" title="Edit"
                                    onclick="openEditModal({{ $row->id }})">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- <button class="btn btn-danger btn-xs"
                                    data-bs-toggle="tooltip" title="Delete"
                                    onclick="deleteRecord({{ $row->id }})">
                                <i class="fas fa-trash"></i>
                            </button> -->
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="examModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="examForm">
            @csrf
            <input type="hidden" id="record_id">

            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label>Test Type</label>
                            <select name="test_type" id="test_type" class="form-control" required>
                                <option value="">Select Test Type</option>
                                @foreach($testTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Exam Name</label>
                            <input type="text" name="exam_name" id="exam_name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label>Exam Date</label>
                            <input type="date" name="exam_date" id="exam_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Long Before</label>
                            <textarea name="long_before" id="long_before" class="form-control"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Exam Time</label>
                            <input type="time" name="exam_time" id="exam_time" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Hours</label>
                            <input type="number" name="hours" id="hours" class="form-control" min="0">
                        </div>

                        <div class="col-md-3">
                            <label>Minutes</label>
                            <input type="number" name="minutes" id="minutes" class="form-control" min="0">
                        </div>
                        <div class="col-md-6">
                            <label>Exam Duration Type</label>
                            <select name="exam_duration_type" id="exam_duration_type" class="form-control" required>
                                <option value="">Select Duration Type</option>
                                @foreach($examDurations as $duration)
                                <option value="{{ $duration->id }}">{{ $duration->title }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-3">
                            <label>Question Type</label>
                            <select name="question_type" id="question_type" class="form-control" required>
                                <option value="">Select Question Type</option>
                                @foreach($questionTypes as $qtype)
                                <option value="{{ $qtype->id }}">{{ $qtype->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Marketing</label>
                            <select name="marketing" id="marketing" class="form-control">
                                <option value="">Select Marketing</option>
                                @foreach($marketingTypes as $m)
                                <option value="{{ $m->id }}">{{ $m->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label>Exam Requirement</label>
                            <textarea name="exam_requirement" id="exam_requirement" class="form-control"></textarea>
                        </div>

                        <div class="col-md-12">
                            <label>Exam Equipment</label>
                            <textarea name="exam_equipment" id="exam_equipment" class="form-control"></textarea>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label>Question Shuffling</label>
                                <select name="question_shuffling" id="question_shuffling" class="form-control">
                                    <option value="1">Shuffle Questions</option>
                                    <option value="0">Don't Shuffle Questions</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label>Previous Button</label>
                                <select name="previous_button" id="previous_button" class="form-control">
                                    <option value="1">Enable Previous Button</option>
                                    <option value="0">Disable Previous Button</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Save
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </div>
        </form>
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

        let modalEl = document.getElementById('examModal');
        let modal = new bootstrap.Modal(modalEl);

        window.openCreateModal = function () {
            $('#record_id').val('');
            $('#examForm')[0].reset();
            $('#modalTitle').text('Add Exam');
            modal.show();
        }

        window.openEditModal = function (id) {
            $.get(`{{ url('exams') }}/${id}/edit`, function (res) {
                $('#record_id').val(res.id);
                $('#test_type').val(res.test_type);
                $('#exam_name').val(res.exam_name);
                $('#exam_date').val(res.exam_date);
                $('#exam_time').val(res.exam_time);
                $('#exam_duration_type').val(res.exam_duration_type);
                $('#hours').val(res.hours);
                $('#minutes').val(res.minutes);
                $('#question_type').val(res.question_type);
                $('#marketing').val(res.marketing);
                $('#exam_requirement').val(res.exam_requirement);
                $('#exam_equipment').val(res.exam_equipment);
                $('#long_before').val(res.long_before);
                $('#question_shuffling').val(res.question_shuffling ? '1' : '0');
                $('#previous_button').val(res.previous_button ? '1' : '0');
                $('#modalTitle').text('Edit Exam');
                modal.show();
            });
        }

        $('#examForm').submit(function (e) {
            e.preventDefault();
            let id = $('#record_id').val();
            let url = id ? `{{ url('exams') }}/${id}` : `{{ route('exams.store') }}`;
            let formData = $(this).serialize();
            if (id) formData += '&_method=PUT';

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function () { location.reload(); },
                error: function (xhr) { alert(xhr.responseJSON?.message ?? 'Something went wrong'); }
            });
        });

        window.deleteRecord = function (id) {
            if (!confirm('Delete record?')) return;
            $.ajax({
                url: `{{ url('exams') }}/${id}`,
                type: 'POST',
                data: {_token:'{{ csrf_token() }}', _method:'DELETE'},
                success: function () { location.reload(); }
            });
        }
    });
</script>
@endpush
