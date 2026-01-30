@extends('layouts.app')
@section('content')

<h3>Responses for Form: {{ $form->title }}</h3>

<div class="card shadow-sm mb-4">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover text-center w-100">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Evaluator / Name</th>
                    <th>Email / Phone</th>
                    <th>Status</th>
                    <th>Submitted At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($form->shares as $k => $share)
                <tr>
                    <td>{{ $k + 1 }}</td>
                    <td>{{ $share->name ?? ($share->student->name ?? '-') }}</td>
                    <td>
                        {{ $share->email ?? '-' }}<br>
                        {{ $share->phone ?? '-' }}
                    </td>
                    <td>
                        @if($share->status === 'W')
                            <span class="badge bg-warning">Waiting</span>
                        @elseif($share->status === 'I')
                            <span class="badge bg-danger">Submitted / Locked</span>
                        @elseif($share->status === 'A')
                            <span class="badge bg-success">Approved</span>
                        @elseif($share->status === 'U')
                            <span class="badge bg-info">Unlocked</span>
                        @endif
                    </td>
                    <td>{{ $share->locked_at?->format('d M, Y H:i') ?? '-' }}</td>
                    <td>
                        @if($share->status === 'W')
                            <button class="btn btn-sm btn-primary" onclick="sendLink('{{ $share->id }}')">Send Link</button>
                        @endif
                        @if($share->status === 'I')
                            <form action="{{ url('evaluation-360/share/'.$share->id.'/approve') }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success">Approve</button>
                            </form>
                            <form action="{{ url('evaluation-360/share/'.$share->id.'/unlock') }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-info">Unlock</button>
                            </form>
                        @endif
                        <button class="btn btn-sm btn-secondary" onclick="viewResponses({{ $share->id }})">View Responses</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ================= VIEW RESPONSES MODAL ================= --}}
<div class="modal fade" id="responsesModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="mb-0">Responses</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="responsesBody"></div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let responsesModal = new bootstrap.Modal(document.getElementById('responsesModal'));

function viewResponses(shareId){
    $.get(`{{ url('evaluation-360') }}/${shareId}/edit`, res => {
        let html = `<h5>Form: ${res.title}</h5>`;
        html += `<hr>`;

        res.sections.forEach(s => {
            let resp = res.responses?.find(r => r.section_id == s.id);
            html += `<div class="card p-2 mb-2">
                        <p><strong>Section:</strong> ${s.section_title}</p>
                        <p><strong>Score / Response:</strong> ${resp?.score ?? '-'}</p>
                     </div>`;
        });

        $('#responsesBody').html(html);
        responsesModal.show();
    });
}

function sendLink(shareId){
    let url = prompt('Copy this link to send via Email/WhatsApp:', `{{ url('360/evaluation') }}/${shareId}`);
    if(url) alert('Link copied! Share it with evaluator.');
}
</script>
@endpush
