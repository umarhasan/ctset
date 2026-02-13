@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>
            DAILY CICU / WARD ROUND
            @if(auth()->user()->hasRole('Trainee'))
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRoundModal">
                <i class="fas fa-plus"></i>
            </button>
            @endif
        </h3>

        <div>
            <a href="{{ route('daily-ward-rounds.export.excel') }}" class="btn btn-success btn-sm">Excel</a>
            <a href="{{ route('daily-ward-rounds.export.pdf') }}" class="btn btn-danger btn-sm">PDF</a>
            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#performanceModal">Performance</button>
        </div>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- DAILY WARD ROUND TABLE --}}
    <div class="card">
        <div class="card-body">
            <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        @if(auth()->user()->hasRole('Admin'))
                        <th>Trainee By</th>
                        <th>Assessor By</th>
                        @endif
                        <th>Date</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Hospital</th>
                        <th>Rotation</th>
                        <th>Involvement</th>
                        @if(auth()->user()->hasAnyRole(['Trainee','Assessor']))
                        <th>Consultant</th>
                        <th>Consultant Signature</th>
                        @endif
                        @if(auth()->user()->hasRole('Admin'))
                        <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($rounds as $i => $r)
                        <tr>
                            <td>{{ $i+1 }}</td>

                            @if(auth()->user()->hasRole('Admin'))
                                <td>{{ $r->user->name ?? '-' }}</td>
                                <td>{{ $r->consultant->name ?? '-' }}</td>
                            @endif

                            <td>{{ \Carbon\Carbon::parse($r->date)->format('d-m-Y') }}</td>
                            <td>{{ $r->from_time }}</td>
                            <td>
                                @if(!$r->to_time)
                                 @if(auth()->user()->hasAnyRole(['Trainee','Assessor']))
                                 <a href="{{ route('daily-ward-rounds.end',$r) }}" onclick="return confirm('End activity?')" class="btn btn-danger btn-sm">End activity</a>
                                @endif
                                @else
                                {{ $r->to_time }}
                                @endif
                            </td>
                            <td>{{ $r->hospital->name ?? '-' }}</td>
                            <td>{{ $r->rotation->short_name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $r->involvement=='A'?'success':'secondary' }}" id="badge-{{ $r->id }}">
                                    {{ $r->involvement=='A'?'Active':'Waiting' }}
                                </span>

                                @if(auth()->user()->hasRole('Assessor'))
                                <br>
                                <label class="switch mt-1">
                                    <input type="checkbox" {{ $r->involvement=='A'?'checked':'' }} 
                                        onchange="toggleInvolvement({{ $r->id }})" id="switch-{{ $r->id }}">
                                    <span class="slider round"></span>
                                </label>
                                @endif
                            </td>

                            {{-- Only for Trainee or Assessor --}}
                            @if(auth()->user()->hasAnyRole(['Trainee','Assessor']))
                                <td>{{ $r->consultant->name ?? $r->consultant_free_text ?? '-' }}</td>
                                <td>
                                    @if(!empty($r->consultant->signature_image))
                                        <img src="{{ route('user.signature.stream', $r->consultant->signature_image) }}" width="80">
                                    @endif
                                </td>
                            @endif

                            @if(auth()->user()->hasRole('Admin'))
                            <td>
                                <form method="POST" action="{{ route('daily-ward-rounds.unmap',$r->id) }}">
                                    @csrf
                                    <button class="btn btn-danger btn-sm">Un-Map</button>
                                </form>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="12">No records found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ADD ROUND MODAL --}}
@if(auth()->user()->hasRole('Trainee'))
<div class="modal fade" id="addRoundModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('daily-ward-rounds.store') }}" class="modal-content">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add Daily Round</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="lat" name="lat">
                <input type="hidden" id="long" name="long">

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
                    <label>Rotation</label>
                    <select name="rotation_id" class="form-control">
                        <option value="">Select</option>
                        @foreach($rotations as $r)
                        <option value="{{ $r->id }}">{{ $r->short_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label>Involvement</label>
                    <select name="involvement" class="form-control" onchange="toggleConsultant(this)" required>
                        <option value="">Select</option>
                        <option value="A">Active</option>
                        <option value="W">Waiting</option>
                    </select>
                </div>

                <div class="mb-2" id="consultantDiv" style="display:none">
                    <label>Consultant</label>
                    <select name="consultant_id" class="form-control">
                        <option value="">Select</option>
                        @foreach($consultants as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>

                    <div class="mt-2">
                        <label>Consultant Free Text</label>
                        <input type="text" name="consultant_free_text" class="form-control">
                    </div>

                    <div id="feesContainer"></div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addFeeRow()">+ Add Fee</button>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- PERFORMANCE MODAL --}}
<div class="modal fade" id="performanceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Performance Analysis</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-pills mb-3" id="perfTabs">
                    <li class="nav-item"><button class="nav-link active" onclick="switchTab('top5')">Top 5 Trainees</button></li>
                    <li class="nav-item"><button class="nav-link" onclick="switchTab('overall')">Overall Performance</button></li>
                </ul>

                <div class="btn-group mb-3">
                    <button class="btn btn-outline-warning" onclick="loadData('all')">All</button>
                    <button class="btn btn-outline-warning" onclick="loadData('1')">1 Month</button>
                    <button class="btn btn-outline-warning" onclick="loadData('3')">3 Months</button>
                    <button class="btn btn-outline-warning" onclick="loadData('6')">6 Months</button>
                </div>

                <div id="loader" class="text-center py-3" style="display:none">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                </div>

                <div id="chartdiv" style="height:350px;"></div>

                <div id="top5Table" class="mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Rounds</th>
                            </tr>
                        </thead>
                        <tbody id="top5Body"></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){

    // Geolocation
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(pos=>{
            document.getElementById('lat').value = pos.coords.latitude;
            document.getElementById('long').value = pos.coords.longitude;
        });
    }

    loadData('all');
});

function toggleConsultant(sel){ document.getElementById('consultantDiv').style.display = sel.value=='A'?'block':'none'; }

function addFeeRow(){
    let d = document.createElement('div');
    d.className='input-group mb-2';
    d.innerHTML=`<input type="number" name="consultant_fees[]" class="form-control">
    <button class="btn btn-danger" type="button" onclick="this.parentNode.remove()">-</button>`;
    document.getElementById('feesContainer').appendChild(d);
}

// Toggle involvement (Assessor)
function toggleInvolvement(id){
    const checkbox = document.getElementById('switch-' + id);
    const badge = document.getElementById('badge-' + id);
    const newValue = checkbox.checked ? 'A' : 'W';

    badge.innerText = newValue === 'A' ? 'Active' : 'Waiting';
    badge.className = 'badge bg-' + (newValue === 'A' ? 'success' : 'secondary');

    fetch(`/daily-ward-rounds/toggle/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ involvement: newValue })
    })
    .then(res => res.json())
    .then(data => {
        if(data.status !== 'success'){
            alert('Something went wrong!');
            checkbox.checked = !checkbox.checked;
            badge.innerText = checkbox.checked ? 'Active' : 'Waiting';
            badge.className = 'badge bg-' + (checkbox.checked ? 'success' : 'secondary');
        }
    })
    .catch(err=>{
        alert('Error updating involvement');
        checkbox.checked = !checkbox.checked;
        badge.innerText = checkbox.checked ? 'Active' : 'Waiting';
        badge.className = 'badge bg-' + (checkbox.checked ? 'success' : 'secondary');
    });
}

// Performance chart
let tab='top5', currentPeriod='all';
function switchTab(t){ tab=t; loadData(currentPeriod); }
function loadData(period){
    currentPeriod=period;
    document.getElementById('loader').style.display='block';
    fetch(`{{ route('daily-ward-rounds.performance') }}?period=${period}`)
    .then(r=>r.json())
    .then(res=>{
        document.getElementById('loader').style.display='none';
        if(tab=='top5'){ drawPie(res.chart_data); fillTop5(res.chart_data); }
        else{ drawBar(res.bar_data); document.getElementById('top5Table').style.display='none'; }
    });
}

function drawPie(data){
    am4core.disposeAllCharts();
    am4core.useTheme(am4themes_animated);
    let chart = am4core.create("chartdiv", am4charts.PieChart);
    chart.data = data;
    let s = chart.series.push(new am4charts.PieSeries());
    s.dataFields.value = "value"; s.dataFields.category = "name";
    chart.legend = new am4charts.Legend();
}

function drawBar(data){
    am4core.disposeAllCharts();
    am4core.useTheme(am4themes_animated);
    let chart = am4core.create("chartdiv", am4charts.XYChart);
    chart.data = data;
    let x = chart.xAxes.push(new am4charts.CategoryAxis());
    x.dataFields.category = "name";
    let y = chart.yAxes.push(new am4charts.ValueAxis());
    let s = chart.series.push(new am4charts.ColumnSeries());
    s.dataFields.valueY = "value"; s.dataFields.categoryX = "name";
}

function fillTop5(data){
    let html='';
    data.forEach((d,i)=>{ html+=`<tr><td>${i+1}</td><td>${d.name}</td><td>${d.type}</td><td>${d.value}</td></tr>`; });
    document.getElementById('top5Body').innerHTML = html;
}
</script>
@endpush