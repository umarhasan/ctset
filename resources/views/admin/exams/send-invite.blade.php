@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">
                <i class="fas fa-paper-plane"></i>
                Send Invite – {{ $exam->exam_name }}
            </h4>
            <small>
                Exam ID: {{ $exam->exam_id }} |
                Date: {{ optional($exam->exam_date)->format('d-m-Y') }}
            </small>
        </div>

        <form method="POST" action="{{ route('exams.send-invite.action', $exam->id) }}">
            @csrf

            <div class="card-body table-responsive">

                @if($trainees->count() > 0)
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th width="60">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trainees as $trainee)
                        <tr>
                            <td>
                                <input type="checkbox"
                                       name="user_ids[]"
                                       value="{{ $trainee->id }}"
                                       class="user-checkbox">
                            </td>
                            <td>{{ $trainee->id }}</td>
                            <td class="text-start">{{ $trainee->name }}</td>
                            <td>{{ $trainee->email }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="alert alert-warning text-center">
                        All trainees are already invited.
                    </div>
                @endif
            </div>

            <div class="card-footer d-flex justify-content-between align-items-center">
                <div>
                    {{ $trainees->links() }}
                </div>
                <div>
                    <a href="{{ route('exams.pending') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane"></i> Send Invite
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('selectAll')?.addEventListener('change', function () {
        document.querySelectorAll('.user-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });
</script>
@endpush
