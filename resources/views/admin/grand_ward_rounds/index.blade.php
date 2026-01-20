@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3>
            GRAND CICU / WARD ROUND
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#roundModal">
                <i class="fa fa-plus"></i>
            </button>
        </h3>


    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- TABLE --}}
    <div class="card">
        <div class="card-body">
            <table id="roundsTable" class="table table-bordered table-striped text-center w-100">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Hospital</th>
                        <th>Rotation</th>
                        <th>Status</th>
                        <th>Consultant</th>
                        <th>Fees</th>
                        <th>Signature</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($rounds as $i=>$r)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($r->date)->format('d-m-Y') }}</td>
                        <td>{{ $r->from_time }}</td>
                        <td>
                            @if(!$r->to_time)
                                <a href="{{ route('grand-ward-rounds.end',$r) }}" class="btn btn-danger btn-sm" onclick="return confirm('End activity?')">End</a>
                            @else
                                {{ $r->to_time }}
                            @endif
                        </td>
                        <td>{{ $r->hospital->name ?? '-' }}</td>
                        <td>{{ $r->rotation->short_name ?? '-' }}</td>
                        <td>
                            @if($r->involvement=='A')
                                <span class="badge bg-success">Active</span>
                            @elseif($r->involvement=='P')
                                <span class="badge bg-warning">Passive</span>
                            @else
                                <span class="badge bg-secondary">Waiting</span>
                            @endif
                        </td>
                        <td>{{ $r->consultant->name ?? '-' }}</td>
                        <td>
                            @if($r->consultant_fees && count($r->consultant_fees) > 0)
                                @foreach($r->consultant_fees as $fee)
                                    <span class="badge bg-info">{{ $fee }}</span><br>
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($r->consultant && $r->consultant->signature_image)
                                <img src="{{ asset('storage/signatures/'.$r->consultant->signature_image) }}" width="70">
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- PERFORMANCE CHART --}}
    <div class="card mt-4">
        <div class="card-header fw-bold">Consultant Performance</div>
        <div class="card-body">
            <canvas id="performanceChart" height="100"></canvas>
        </div>
    </div>

    {{-- MODAL --}}
    <div class="modal fade" id="roundModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('grand-ward-rounds.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Round</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>From Time</label>
                            <input type="time" name="from_time" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Hospital</label>
                            <select name="hospital_id" class="form-control" required>
                                @foreach($hospitals as $h)
                                    <option value="{{ $h->id }}">{{ $h->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label>Rotation</label>
                            <select name="rotation_id" class="form-control">
                                <option value="">Select</option>
                                @foreach($rotations as $rot)
                                    <option value="{{ $rot->id }}">{{ $rot->short_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label>Involvement</label>
                            <select name="involvement" class="form-control" onchange="toggleConsultant(this)">
                                <option value="">Select</option>
                                <option value="A">Consultant</option>
                                <option value="W">Without Consultant</option>
                            </select>
                        </div>

                        <div id="consultantDiv" style="display:none">
                            <div class="mb-2">
                                <label>Consultant</label>
                                <select name="consultant_id" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($consultants as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2" id="feesContainer">
                                <label>Consultant Fees</label>
                                <div class="input-group mb-2 feeRow">
                                    <input type="number" name="consultant_fees[]" class="form-control" placeholder="Fee">
                                    <button type="button" class="btn btn-success" onclick="addFeeRow()">+</button>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="lat" id="lat">
                        <input type="hidden" name="long" id="long">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')


<script>

/* TOGGLE CONSULTANT */
function toggleConsultant(sel){
    document.getElementById('consultantDiv').style.display = (sel.value=='A') ? 'block' : 'none';
}

/* FEES ROWS */
function addFeeRow(){
    let div = document.createElement('div');
    div.className = 'input-group mb-2 feeRow';
    div.innerHTML = `
        <input type="number" name="consultant_fees[]" class="form-control">
        <button type="button" class="btn btn-danger" onclick="this.parentNode.remove()">-</button>
    `;
    document.getElementById('feesContainer').appendChild(div);
}

/* GEO LOCATION */
if(navigator.geolocation){
    navigator.geolocation.getCurrentPosition(pos=>{
        document.getElementById('lat').value = pos.coords.latitude;
        document.getElementById('long').value = pos.coords.longitude;
    });
}

/* PERFORMANCE CHART */
fetch('grand-ward-rounds/performance/data')
.then(r=>r.json())
.then(data=>{
    new Chart(document.getElementById('performanceChart'),{
        type:'bar',
        data:{
            labels:data.map(d=>d.consultant.name),
            datasets:[{
                label:'Total Rounds',
                data:data.map(d=>d.total),
                backgroundColor:'green'
            }]
        }
    });
});
</script>
@endpush
