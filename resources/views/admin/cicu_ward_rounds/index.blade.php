@extends('layouts.app')
@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>
            GRAND CICU / WARD ROUND
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRoundModal">
                <i class="fas fa-plus"></i>
            </button>
        </h3>

        <div>
            <a href="{{ route('cicu-ward-rounds.export.excel') }}" class="btn btn-success btn-sm">Excel</a>
            <a href="{{ route('cicu-ward-rounds.export.pdf') }}" class="btn btn-danger btn-sm">PDF</a>
            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#performanceModal">Performance</button>
        </div>
    </div>

    {{-- GRAND WARD ROUND TABLE --}}
    <div class="card">
        <div class="card-body">
            <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>By</th>
                        <th>Date</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Hospital</th>
                        <th>Involvement</th>
                        <th>Consultant Signature</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rounds as $i => $r)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $r->user->name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($r->date)->format('d-m-Y') }}</td>
                            <td>{{ $r->from_time }}</td>
                            <td>
                                @if(!$r->to_time)
                                <a href="{{ route('cicu-ward-rounds.end',$r) }}" onclick="return confirm('End activity?')" class="btn btn-danger btn-sm">End activity</a>
                                @else
                                {{ $r->to_time }}
                                @endif
                            </td>
                            <td>{{ $r->hospital->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $r->involvement=='A'?'success':'secondary' }}">
                                    {{ $r->involvement=='A'?'Active':'Waiting' }}
                                </span>
                            </td>
                            <td>
                                @if($r->consultant_signature)
                                    <img src="{{ asset('sign/'.$r->consultant_signature) }}" width="80">
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8">No records found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
{{-- ADD ROUND MODAL --}}
<div class="modal fade" id="addRoundModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('cicu-ward-rounds.store') }}" class="modal-content">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add CICU Ward Round</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-2">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="mb-2">
                    <label>From Time</label>
                    <input type="time" name="from_time" class="form-control" value="{{ date('H:i') }}" required>
                </div>

                <div class="mb-2">
                    <label>Hospital</label>
                    <select name="hospital_id" class="form-control" required>
                        <option value="">Select</option>
                        @foreach($hospitals as $h)
                        <option value="{{ $h->id }}">{{ $h->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label>Involvement</label>
                    <select name="involvement" class="form-control" required>
                        <option value="">Select</option>
                        <option value="A">Active</option>
                        <option value="W">Waiting</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
<div id="performanceModal" class="modal fade">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">CICU Ward Rounds Performance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                {{-- Period buttons --}}
                <div class="mb-3">
                    <button class="btn btn-sm btn-outline-primary cicu-period-btn" data-period="1">1 Month</button>
                    <button class="btn btn-sm btn-outline-primary cicu-period-btn" data-period="3">3 Months</button>
                    <button class="btn btn-sm btn-outline-primary cicu-period-btn" data-period="6">6 Months</button>
                    <button class="btn btn-sm btn-outline-primary cicu-period-btn" data-period="all">All</button>
                </div>

                {{-- Tabs --}}
                <ul class="nav nav-tabs mb-3" id="chartTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pie-tab" data-bs-toggle="tab" data-bs-target="#pieChartTab" type="button" role="tab">Pie Chart</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="bar-tab" data-bs-toggle="tab" data-bs-target="#barChartTab" type="button" role="tab">Bar Chart</button>
                    </li>
                </ul>

                {{-- Tab contents --}}
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="pieChartTab" role="tabpanel">
                        <div id="cicuPieChartDiv" style="height:400px"></div>
                    </div>
                    <div class="tab-pane fade" id="barChartTab" role="tabpanel">
                        <div id="cicuBarChartDiv" style="height:400px"></div>
                    </div>
                </div>

                {{-- Top 5 Table --}}
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody id="top5Body"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/common.js') }}"></script>
<script src="{{ asset('js/cicu.js') }}"></script>
@endpush
