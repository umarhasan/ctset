@extends('layouts.app')

<style>
table.dataTable { table-layout: fixed; }
.table th, .table td {
    vertical-align: middle !important;
    white-space: nowrap;
}
.table thead th {
    font-weight: 600;
    text-transform: capitalize;
}
.dataTables_filter input {
    border-radius: 20px;
    padding: 4px 12px;
}
.dataTables_length select {
    border-radius: 20px;
}
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
        <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
             <thead class="table-dark">
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
            @foreach($records as $row)
                <tr>
                    <td>{{ $row->exam_id }}</td>
                    <td>{{ $row->testType->title ?? '-' }}</td>
                    <td class="text-start">{{ $row->exam_name }}</td>
                    <td class="text-center">
                        @foreach($row->questionTypes as $qt)
                            <div>{{ $qt->title }}</div>

                            @if(!$loop->last && $row->questionTypes->count() > 1)
                                <hr class="my-1 mx-auto" style="width:60%">
                            @endif
                        @endforeach
                    </td>

                    <td>{{ \Carbon\Carbon::parse($row->exam_date)->format('d-m-Y') }}</td>
                    <td>{{ ($row->hours ?? 0).'H '.($row->minutes ?? 0).'M' }}</td>
                    <td>{{ $row->examDuration->title ?? '-' }}</td>
                    <td>{{ $row->exam_time ?? '-' }}</td>
                    <td>{{ $row->long_before ?? '-' }}</td>
                    <td>{{ $row->created_at->format('d-m-Y') }}</td>
                    <td>
                        <button class="btn btn-warning btn-xs"
                                onclick="openEditModal({{ $row->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL -->
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
                                <option value="">Select</option>
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
                            <label>Exam Time</label>
                            <input type="time" name="exam_time" id="exam_time" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Hours</label>
                            <input type="number" name="hours" id="hours" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Minutes</label>
                            <input type="number" name="minutes" id="minutes" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Exam Duration</label>
                            <select name="exam_duration_type" id="exam_duration_type" class="form-control">
                                @foreach($examDurations as $d)
                                    <option value="{{ $d->id }}">{{ $d->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Question Types</label><br>
                            @foreach($questionTypes as $q)
                                <label class="me-3">
                                    <input type="checkbox"
                                           class="question-type-checkbox"
                                           name="question_types[]"
                                           value="{{ $q->id }}">
                                    {{ $q->title }}
                                </label>
                            @endforeach
                        </div>

                        <div class="col-md-6">
                            <label>Marketing</label>
                            <select name="marketing" id="marketing" class="form-control">
                                @foreach($marketingTypes as $m)
                                    <option value="{{ $m->id }}">{{ $m->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label>Long Before</label>
                            <textarea name="long_before" id="long_before" class="form-control"></textarea>
                        </div>

                        <div class="col-md-12">
                            <label>Exam Requirement</label>
                            <textarea name="exam_requirement" id="exam_requirement" class="form-control"></textarea>
                        </div>

                        <div class="col-md-12">
                            <label>Exam Equipment</label>
                            <textarea name="exam_equipment" id="exam_equipment" class="form-control"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label>Question Shuffling</label>
                            <select name="question_shuffling" id="question_shuffling" class="form-control">
                                <option value="1">Enable</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Previous Button</label>
                            <select name="previous_button" id="previous_button" class="form-control">
                                <option value="1">Enable</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Save</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {

    $('#examTable1').DataTable({scrollX:true});

    let modal = new bootstrap.Modal(document.getElementById('examModal'));

    window.openCreateModal = function () {
        $('#examForm')[0].reset();
        $('#record_id').val('');
        $('.question-type-checkbox').prop('checked', false);
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
            $('#hours').val(res.hours);
            $('#minutes').val(res.minutes);
            $('#exam_duration_type').val(res.exam_duration_type);
            $('#marketing').val(res.marketing);
            $('#long_before').val(res.long_before);
            $('#exam_requirement').val(res.exam_requirement);
            $('#exam_equipment').val(res.exam_equipment);
            $('#question_shuffling').val(res.question_shuffling ? 1 : 0);
            $('#previous_button').val(res.previous_button ? 1 : 0);

            $('.question-type-checkbox').prop('checked', false);
            res.question_types.forEach(q =>
                $('.question-type-checkbox[value="'+q.id+'"]').prop('checked', true)
            );

            $('#modalTitle').text('Edit Exam');
            modal.show();
        });
    }

    $('#examForm').submit(function (e) {
        e.preventDefault();

        let id = $('#record_id').val();
        let url = id ? `{{ url('exams') }}/${id}` : `{{ route('exams.store') }}`;
        let data = $(this).serialize();
        if (id) data += '&_method=PUT';

        $.post(url, data, () => location.reload());
    });
});
</script>
@endpush
