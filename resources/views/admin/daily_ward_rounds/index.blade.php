@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>
            DAILY CICU / WARD ROUND
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRoundModal">
                <i class="fas fa-plus"></i>
            </button>
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
            <table id="examTable" class="table table-bordered table-hover text-center w-100">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>By</th>
                        <th>Date</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Hospital</th>
                        <th>Rotation</th>
                        <th>Involvement</th>
                        <th>Consultant</th>
                        <th>Action</th>
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
                                <a href="{{ route('daily-ward-rounds.end',$r) }}" onclick="return confirm('End activity?')" class="btn btn-danger btn-sm">End</a>
                                @else
                                {{ $r->to_time }}
                                @endif
                            </td>
                            <td>{{ $r->hospital->name ?? '-' }}</td>
                            <td>{{ $r->rotation->short_name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $r->involvement=='A'?'success':'secondary' }}">
                                    {{ $r->involvement=='A'?'Active':'Waiting' }}
                                </span>
                            </td>
                            <td>{{ $r->consultant->name ?? $r->consultant_free_text ?? '-' }}</td>
                            <td>
                                <form method="POST" action="{{ route('daily-ward-rounds.destroy',$r) }}">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10">No records found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ADD ROUND MODAL --}}
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
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

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
                    <li class="nav-item"><button class="nav-link active" onclick="switchTab('top5')">Over All Top 5 Trainees</button></li>
                    <li class="nav-item"><button class="nav-link" onclick="switchTab('overall')">Over all Performance</button></li>
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
                         <thead class="table-dark">
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
document.addEventListener('DOMContentLoaded',function(){

    // Geolocation
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(pos=>{
            document.getElementById('lat').value=pos.coords.latitude;
            document.getElementById('long').value=pos.coords.longitude;
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

// Performance Chart
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
    let chart=am4core.create("chartdiv",am4charts.PieChart);
    chart.data=data;
    let s=chart.series.push(new am4charts.PieSeries());
    s.dataFields.value="value"; s.dataFields.category="name";
    chart.legend=new am4charts.Legend();
}

function drawBar(data){
    am4core.disposeAllCharts();
    am4core.useTheme(am4themes_animated);
    let chart=am4core.create("chartdiv",am4charts.XYChart);
    chart.data=data;
    let x=chart.xAxes.push(new am4charts.CategoryAxis());
    x.dataFields.category="name";
    let y=chart.yAxes.push(new am4charts.ValueAxis());
    let s=chart.series.push(new am4charts.ColumnSeries());
    s.dataFields.valueY="value"; s.dataFields.categoryX="name";
}

function fillTop5(data){
    let html='';
    data.forEach((d,i)=>{ html+=`<tr><td>${i+1}</td><td>${d.name}</td><td>${d.type}</td><td>${d.value}</td></tr>`; });
    document.getElementById('top5Body').innerHTML=html;
}

function toggleInvolvement(id){
    // Call your API to toggle involvement status
}
</script>
<script type="text/javascript" src="https://sellfy.com/js/api_buttons.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/anypicker@latest/dist/anypicker-all.min.css" />
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/anypicker@latest/dist/anypicker.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/anypicker@latest/dist/i18n/anypicker-i18n.js"></script>
@endpush
