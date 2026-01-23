@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">
            GRAND CICU / WARD ROUND
            <button class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#addRoundModal">
                <i class="fas fa-plus"></i>
            </button>
        </h3>

        <div>
            <a href="{{ route('grand-ward-rounds.export.excel') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('grand-ward-rounds.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#performanceModal">
                <i class="fas fa-chart-pie"></i> Performance
            </button>
        </div>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- TABLE --}}
    <div class="card">
        <div class="card-body">
            <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
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
                        <td>{{ \Carbon\Carbon::parse($r->date)->format('d-m-Y') }}</td>
                        <td>{{ $r->from_time }}</td>
                        <td>
                            @if(!$r->to_time)
                                <a href="{{ route('grand-ward-rounds.end',$r) }}"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('End activity?')">
                                    End activity
                                </a>
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
                        <td>{{ $r->consultant->name ?? '-' }}</td>
                        <td>
                            <form method="POST" action="{{ route('grand-ward-rounds.destroy',$r) }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Delete?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9">No records found</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ================= ADD ROUND MODAL ================= --}}
<div class="modal fade" id="addRoundModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('grand-ward-rounds.store') }}" class="modal-content">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add Round</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="lat" name="lat" value="" />
                <input type="hidden" id="long" name="long" value="" />
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

                    <div id="feesContainer"></div>

                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addFeeRow()">
                        <i class="fas fa-plus"></i> Add Fee
                    </button>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="performanceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-warning">
                <h5 class="modal-title">Performance Analysis</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <ul class="nav nav-pills justify-content-center mb-3" id="perfTabs">
                    <li class="nav-item">
                        <button class="nav-link active" onclick="switchTab('top5')">Over All Performance with Top 5 Students</button>
                    </li>
                    <li class="nav-item ms-2">
                        <button class="nav-link" onclick="switchTab('overall')">Over All Performance</button>
                    </li>
                </ul>

                <div class="text-center mb-3">
                    <div class="btn-group">
                        <button class="btn btn-outline-warning period-btn active" onclick="loadData('all')">All</button>
                        <button class="btn btn-outline-warning period-btn" onclick="loadData('1')">1 Month</button>
                        <button class="btn btn-outline-warning period-btn" onclick="loadData('3')">3 Months</button>
                        <button class="btn btn-outline-warning period-btn" onclick="loadData('6')">6 Months</button>
                    </div>
                </div>

                <div class="text-center py-3" id="loader" style="display:none">
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
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

<script>
let tab = 'top5';
let currentPeriod = 'all';

/* DEFAULT */
document.addEventListener('DOMContentLoaded', function () {
    loadData('all');
    getlocation();
});

/* CONSULTANT */
function toggleConsultant(sel){
    document.getElementById('consultantDiv').style.display = sel.value=='A'?'block':'none';
}

/* FEES */
function addFeeRow(){
    let d=document.createElement('div');
    d.className='input-group mb-2';
    d.innerHTML=`<input type="number" name="consultant_fees[]" class="form-control">
    <button class="btn btn-danger" type="button" onclick="this.parentNode.remove()">-</button>`;
    feesContainer.appendChild(d);
}

/* TAB */
function switchTab(t){
    tab=t;
    document.querySelectorAll('#perfTabs .nav-link').forEach(b=>b.classList.remove('active'));
    event.target.classList.add('active');
    loadData(currentPeriod);
}

/* LOAD */
function loadData(period){
    currentPeriod=period;
    loader.style.display='block';

    fetch(`{{ route('grand-ward-rounds.performance') }}?period=${period}`)
    .then(r=>r.json())
    .then(res=>{
        loader.style.display='none';
        if(tab=='top5'){ drawPie(res.chart_data); fillTop5(res.chart_data); }
        else{ drawBar(res.bar_data); top5Table.style.display='none'; }
    });
}

/* PIE */
function drawPie(data){
    am4core.disposeAllCharts();
    am4core.useTheme(am4themes_animated);
    let chart=am4core.create("chartdiv",am4charts.PieChart);
    chart.data=data;
    let s=chart.series.push(new am4charts.PieSeries());
    s.dataFields.value="value";
    s.dataFields.category="name";
    chart.legend=new am4charts.Legend();
}

/* BAR */
function drawBar(data){
    am4core.disposeAllCharts();
    am4core.useTheme(am4themes_animated);
    let chart=am4core.create("chartdiv",am4charts.XYChart);
    chart.data=data;
    let x=chart.xAxes.push(new am4charts.CategoryAxis());
    x.dataFields.category="name";
    let y=chart.yAxes.push(new am4charts.ValueAxis());
    let s=chart.series.push(new am4charts.ColumnSeries());
    s.dataFields.valueY="value";
    s.dataFields.categoryX="name";
}

function fillTop5(data){
    let html='';
    data.forEach((d,i)=>{
        html+=`<tr><td>${i+1}</td><td>${d.name}</td><td>${d.type}</td><td>${d.value}</td></tr>`;
    });
    top5Body.innerHTML=html;
}


function getlocation() {
    if (!navigator.geolocation) {
        console.warn('Geolocation not supported');
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function(position){
            document.getElementById('lat').value = position.coords.latitude;
            document.getElementById('long').value = position.coords.longitude;
        },
        function(){
            console.warn('Location permission denied');
        }
    );
}
</script>
@endpush
