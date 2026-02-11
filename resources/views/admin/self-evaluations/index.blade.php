@extends('layouts.app')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Self Evaluation Forms</h3>
        <div class="ms-auto">
            @can('self-evaluations-create')
                <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                    <i class="fa fa-plus"></i> Add Self Evaluation Forms
                </button>
            @endcan
        </div>
    </div>

    <div class="card-body">
        <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
             <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Goals</th>
                    <th>Goal-Plan</th>
                    <th>Question</th>
                    <th>Status</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($evaluations as $evaluation)
                <tr>
                    <td>{{ $evaluation->id }}</td>
                    <td>{{ $evaluation->title }}</td>
                    <td>{!! Str::limit($evaluation->goals, 50) !!}</td>
                    <td>{{ count(json_decode($evaluation->goal_plan_actions) ?? []) }} items</td>
                    <td>{{ count(json_decode($evaluation->question_actions) ?? []) }} items</td>
                    <td>
                        @if($evaluation->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        @can('self-evaluations-update')
                            <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $evaluation->id }})">
                                <i class="fa fa-edit"></i>
                            </button>
                        @endcan
                        @can('self-evaluations-delete')
                            <button class="btn btn-danger btn-sm" onclick="deleteRecord({{ $evaluation->id }})">
                                <i class="fa fa-trash"></i>
                            </button>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $evaluations->links() }}
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="masterModal">
    <div class="modal-dialog modal-xl">
        <form id="masterForm">
            @csrf
            <input type="hidden" id="record_id">

            <div class="modal-content">
                <div class="modal-header bg-primary text-white justify-content-center position-relative">
                    <h5 id="modalTitle" class="modal-title text-center w-100">Add Self Evaluation Form</h5>
                    <button type="button" class="btn-close position-absolute top-0 end-0 me-2 mt-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Title -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label>Title *</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                    </div>

                    <!-- Goals with CKEditor -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label>Goals</label>
                            <textarea name="goals" id="goals" class="form-control"></textarea>
                        </div>
                    </div>

                    <!-- Goal-Plan Section -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-center flex-grow-1">Goal - Plan</h6>
                                    <button type="button" class="btn btn-success btn-sm" onclick="addGoalPlanAction()">
                                        <i class="fa fa-plus"></i> Add More
                                    </button>
                                </div>
                                <div class="card-body" id="goalPlanActionContainer">
                                    <div class="row goal-plan-action-item mb-2 align-items-center">
                                        <div class="col-md-5">
                                            <input type="text" name="goal_plan_actions[0][goal]" class="form-control" placeholder="Goal">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" name="goal_plan_actions[0][plan]" class="form-control" placeholder="Plan">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeGoalPlanAction(this)" disabled>
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Question Section -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-center flex-grow-1">Question</h6>
                                    <button type="button" class="btn btn-success btn-sm" onclick="addQuestionAction()">
                                        <i class="fa fa-plus"></i> Add More
                                    </button>
                                </div>
                                <div class="card-body" id="questionActionContainer">
                                    <div class="row question-action-item mb-2 align-items-center">
                                        <div class="col-md-4">
                                            <input type="text" name="question_actions[0][title]" class="form-control" placeholder="Title">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="question_actions[0][question]" class="form-control" placeholder="Question">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestionAction(this)" disabled>
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label>Status *</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
let masterModal;
let goalPlanActionCount = 1;
let questionActionCount = 1;

$(document).ready(function() {
    // Initialize DataTable
    $('#evaluationsTable').DataTable({
        paging: false,
        info: false,
        searching: true,
        ordering: true
    });

    // Initialize modal
    masterModal = new bootstrap.Modal(document.getElementById('masterModal'));

    // Initialize Summernote for Goals
    function initSummernote() {
        $('#goals').summernote({
            height: 200,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    }

    // Destroy Summernote
    function destroySummernote() {
        if ($('#goals').summernote('isEmpty')) {
            $('#goals').summernote('destroy');
        } else {
            $('#goals').summernote('destroy');
        }
    }

    // Open Create Modal
    window.openCreateModal = function() {
        $('#record_id').val('');
        $('#masterForm')[0].reset();
        $('#modalTitle').text('Add Self Evaluation Form');
        $('#status').val('active');

        // Reset goal-plan container
        $('#goalPlanActionContainer').html(`
            <div class="row goal-plan-action-item mb-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="goal_plan_actions[0][goal]" class="form-control" placeholder="Goal">
                </div>
                <div class="col-md-5">
                    <input type="text" name="goal_plan_actions[0][plan]" class="form-control" placeholder="Plan">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeGoalPlanAction(this)" disabled>
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `);
        goalPlanActionCount = 1;

        // Reset question container
        $('#questionActionContainer').html(`
            <div class="row question-action-item mb-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="question_actions[0][title]" class="form-control" placeholder="Title">
                </div>
                <div class="col-md-6">
                    <input type="text" name="question_actions[0][question]" class="form-control" placeholder="Question">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestionAction(this)" disabled>
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `);
        questionActionCount = 1;

        // Initialize Summernote
        destroySummernote();
        setTimeout(() => {
            $('#goals').val('');
            initSummernote();
        }, 100);

        masterModal.show();
    };

    // Open Edit Modal
    window.openEditModal = function(id) {
        $.ajax({
            url: `self-evaluations/${id}/edit`,
            method: 'GET',
            success: function(response) {
                $('#record_id').val(response.id);
                $('#title').val(response.title);
                $('#status').val(response.status);
                $('#modalTitle').text('Edit Self Evaluation Form');

                // Set Goals with Summernote
                destroySummernote();
                setTimeout(() => {
                    $('#goals').val(response.goals);
                    initSummernote();
                }, 100);

                // Load Goal-Plan Actions
                let goalPlanActions = response.goal_plan_actions ? JSON.parse(response.goal_plan_actions) : [];
                let goalHtml = '';
                if (goalPlanActions.length > 0) {
                    goalPlanActions.forEach((item, index) => {
                        goalHtml += generateGoalPlanRow(index, item.goal || '', item.plan || '');
                    });
                    goalPlanActionCount = goalPlanActions.length;
                } else {
                    goalHtml = generateGoalPlanRow(0, '', '', true);
                    goalPlanActionCount = 1;
                }
                $('#goalPlanActionContainer').html(goalHtml);

                // Load Question Actions
                let questionActions = response.question_actions ? JSON.parse(response.question_actions) : [];
                let questionHtml = '';
                if (questionActions.length > 0) {
                    questionActions.forEach((item, index) => {
                        questionHtml += generateQuestionActionRow(index, item.title || '', item.question || '');
                    });
                    questionActionCount = questionActions.length;
                } else {
                    questionHtml = generateQuestionActionRow(0, '', '', true);
                    questionActionCount = 1;
                }
                $('#questionActionContainer').html(questionHtml);

                masterModal.show();
            },

        });
    };

    // Submit Form
    $('#masterForm').on('submit', function(e) {
        e.preventDefault();

        let id = $('#record_id').val();
        let url = id ? `self-evaluations/${id}` : `self-evaluations`;
        let method = id ? 'PUT' : 'POST';

        // Get Summernote content
        let goalsContent = $('#goals').summernote('code');

        // Prepare form data
        let formData = {
            _token: '{{ csrf_token() }}',
            _method: method,
            title: $('#title').val(),
            goals: goalsContent,
            status: $('#status').val(),
            goal_plan_actions: getGoalPlanActions(),
            question_actions: getQuestionActions(),
        };

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // toastr.success(response.message);
                    masterModal.hide();
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                } else {
                    toastr.error('Something went wrong!');
                }
            }
        });
    });

    // Delete Record
    window.deleteRecord = function(id) {
        if (!confirm('Are you sure you want to delete this record?')) return;

        $.ajax({
            url: `self-evaluations/${id}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            },
            error: function() {
                toastr.error('Something went wrong!');
            }
        });
    };

    // Goal-Plan Functions
    window.addGoalPlanAction = function() {
        $('#goalPlanActionContainer').append(generateGoalPlanRow(goalPlanActionCount, '', ''));
        goalPlanActionCount++;

        // Enable delete button for first item if there are more than 1
        if ($('#goalPlanActionContainer .goal-plan-action-item').length > 1) {
            $('#goalPlanActionContainer .goal-plan-action-item:first .btn-danger').prop('disabled', false);
        }
    };

    window.removeGoalPlanAction = function(button) {
        $(button).closest('.goal-plan-action-item').remove();

        // Re-index names
        $('#goalPlanActionContainer .goal-plan-action-item').each(function(index) {
            $(this).find('input[name*="[goal]"]').attr('name', `goal_plan_actions[${index}][goal]`);
            $(this).find('input[name*="[plan]"]').attr('name', `goal_plan_actions[${index}][plan]`);
        });

        // Disable delete button for first item if only one remains
        if ($('#goalPlanActionContainer .goal-plan-action-item').length === 1) {
            $('#goalPlanActionContainer .goal-plan-action-item:first .btn-danger').prop('disabled', true);
        }

        goalPlanActionCount = $('#goalPlanActionContainer .goal-plan-action-item').length;
    };

    function generateGoalPlanRow(index, goal = '', plan = '', isFirst = false) {
        return `
        <div class="row goal-plan-action-item mb-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="goal_plan_actions[${index}][goal]" class="form-control" placeholder="Goal" value="${goal}">
            </div>
            <div class="col-md-5">
                <input type="text" name="goal_plan_actions[${index}][plan]" class="form-control" placeholder="Plan" value="${plan}">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeGoalPlanAction(this)" ${isFirst ? 'disabled' : ''}>
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>`;
    }

    function getGoalPlanActions() {
        let actions = [];
        $('#goalPlanActionContainer .goal-plan-action-item').each(function() {
            let goal = $(this).find('input[name*="[goal]"]').val();
            let plan = $(this).find('input[name*="[plan]"]').val();
            if (goal || plan) {
                actions.push({
                    goal: goal,
                    plan: plan
                });
            }
        });
        return actions;
    }

    // Question Functions
    window.addQuestionAction = function() {
        $('#questionActionContainer').append(generateQuestionActionRow(questionActionCount, '', ''));
        questionActionCount++;

        // Enable delete button for first item if there are more than 1
        if ($('#questionActionContainer .question-action-item').length > 1) {
            $('#questionActionContainer .question-action-item:first .btn-danger').prop('disabled', false);
        }
    };

    window.removeQuestionAction = function(button) {
        $(button).closest('.question-action-item').remove();

        // Re-index names
        $('#questionActionContainer .question-action-item').each(function(index) {
            $(this).find('input[name*="[title]"]').attr('name', `question_actions[${index}][title]`);
            $(this).find('input[name*="[question]"]').attr('name', `question_actions[${index}][question]`);
        });

        // Disable delete button for first item if only one remains
        if ($('#questionActionContainer .question-action-item').length === 1) {
            $('#questionActionContainer .question-action-item:first .btn-danger').prop('disabled', true);
        }

        questionActionCount = $('#questionActionContainer .question-action-item').length;
    };

    function generateQuestionActionRow(index, title = '', question = '', isFirst = false) {
        return `
        <div class="row question-action-item mb-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="question_actions[${index}][title]" class="form-control" placeholder="Title" value="${title}">
            </div>
            <div class="col-md-6">
                <input type="text" name="question_actions[${index}][question]" class="form-control" placeholder="Question" value="${question}">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestionAction(this)" ${isFirst ? 'disabled' : ''}>
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>`;
    }

    function getQuestionActions() {
        let actions = [];
        $('#questionActionContainer .question-action-item').each(function() {
            let title = $(this).find('input[name*="[title]"]').val();
            let question = $(this).find('input[name*="[question]"]').val();
            if (title || question) {
                actions.push({
                    title: title,
                    question: question
                });
            }
        });
        return actions;
    }

    // Destroy Summernote when modal is hidden
    $('#masterModal').on('hidden.bs.modal', function() {
        destroySummernote();
    });
});
</script>
@endpush
