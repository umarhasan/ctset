@extends('layouts.app')

@section('title', 'Reports Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Reports Management</h4>
                </div>
                <div class="card-body">
                    <div id="tabs">
                        <ul>
                            <li><a href="#tabs-1">Search</a></li>
                            <li><a href="#tabs-2">Exception Reports</a></li>
                            <li><a href="#tabs-3">Summary Reports</a></li>
                        </ul>

                        <!-- Tab 1: Search -->
                        <div id="tabs-1">
                            <form id="searchForm">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label>Trainee:</label>
                                        <div class="frmSearch">
                                            <input type="hidden" name="user_id" id="user_id">
                                            <input type="text" class="form-control" id="search-box" placeholder="Search trainee...">
                                            <div id="suggesstion-box"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Start Date:</label>
                                        <input type="text" class="form-control datepicker" name="sdate" id="sdate">
                                    </div>
                                    <div class="col-md-2">
                                        <label>End Date:</label>
                                        <input type="text" class="form-control datepicker" name="edate" id="edate">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Module:</label>
                                        <select class="form-control" name="module" id="module">
                                            <option value="0">All</option>
                                            <option value="C">Clinical Activities</option>
                                            <option value="S">Scientific Meetings</option>
                                            <option value="M">MATCVS</option>
                                            <option value="D">Department / Unit</option>
                                            <option value="E">Evaluation Form</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Department:</label>
                                        <select class="form-control" name="dpt" id="dpt">
                                            <option value="0">Please Select..</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-2">
                                        <label>Status:</label>
                                        <select class="form-control" name="involvement" id="status">
                                            <option value="0">Please Select..</option>
                                            <option value="W">Waiting</option>
                                            <option value="A">Active</option>
                                            <option value="P">Passive</option>
                                            <option value="I">In-Progress</option>
                                            <option value="U">Under Review</option>
                                            <option value="R">Reviewed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Hospital:</label>
                                        <select class="form-control" name="hospital" id="hospital">
                                            <option value="0">Please Select..</option>
                                            @foreach($hospitals as $hospital)
                                                <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary" onclick="searchReports()">SEARCH</button>
                                        <button type="reset" class="btn btn-secondary">Cancel</button>
                                    </div>
                                </div>
                            </form>

                            <div class="mt-4" id="searchResultDiv" style="display:none;">
                                <table id="searchTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S#</th>
                                            <th>Trainee</th>
                                            <th>Date</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Hours</th>
                                            <th>Status</th>
                                            <th>Hospital</th>
                                            <th>Department</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab 2: Exception Reports -->
                        <div id="tabs-2">
                            <form id="exceptionForm">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label>Report Type:</label>
                                        <select class="form-control" name="report_type" id="report_type">
                                            <option value="D1">Last Day Report</option>
                                            <option value="D">Today Report</option>
                                            <option value="W">Weekly Report</option>
                                            <option value="M">Monthly Report</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Module:</label>
                                        <select class="form-control" name="module_exp" id="module_exp">
                                            <option value="0">All</option>
                                            <option value="C">Clinical Activities</option>
                                            <option value="S">Scientific Meetings</option>
                                            <option value="M">MATCVS</option>
                                            <option value="D">Department / Unit</option>
                                            <option value="E">Evaluation Form</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Trainee:</label><br>
                                        <input type="radio" name="rd_type" value="all" checked> All
                                        <input type="radio" name="rd_type" value="tran" class="ml-3"> Trainee
                                    </div>
                                    <div class="col-md-3">
                                        <label>Trainee Name:</label>
                                        <div class="frmSearch">
                                            <input type="hidden" name="user_id_exp" id="user_id_exp">
                                            <input type="text" class="form-control" id="search-box2" placeholder="Select trainee...">
                                            <div id="suggesstion-box2"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="is_absent" id="is_absent" value="Y">
                                            <label class="form-check-label" for="is_absent">Absent Report</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary" onclick="getExceptionReports()">SEARCH</button>
                                        <button type="reset" class="btn btn-secondary">Cancel</button>
                                    </div>
                                </div>
                            </form>

                            <div class="mt-4" id="exceptionResultDiv" style="display:none;">
                                <table id="exceptionTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S#</th>
                                            <th>Trainee</th>
                                            <th>Date</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Hours</th>
                                            <th>Status</th>
                                            <th>Hospital</th>
                                            <th>Department</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab 3: Summary Reports -->
                        <div id="tabs-3">
                            <form id="summaryForm">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label>Report Type:</label>
                                        <select class="form-control" name="report_type_summary" id="report_type_summary">
                                            <option value="D1">Last Day Report</option>
                                            <option value="D">Today Report</option>
                                            <option value="W">Weekly Report</option>
                                            <option value="M">Monthly Report</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Module:</label>
                                        <select class="form-control" name="module_summary" id="module_summary">
                                            <option value="0">All</option>
                                            <option value="C">Clinical Activities</option>
                                            <option value="S">Scientific Meetings</option>
                                            <option value="M">MATCVS</option>
                                            <option value="D">Department / Unit</option>
                                            <option value="E">Evaluation Form</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Trainee:</label><br>
                                        <input type="radio" name="rd_type_summary" value="all" checked> All
                                        <input type="radio" name="rd_type_summary" value="tran" class="ml-3"> Trainee
                                    </div>
                                    <div class="col-md-3">
                                        <label>Trainee Name:</label>
                                        <div class="frmSearch">
                                            <input type="hidden" name="user_id_summary" id="user_id_summary">
                                            <input type="text" class="form-control" id="search-box3" placeholder="Select trainee...">
                                            <div id="suggesstion-box3"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary" onclick="getSummaryReports()">SEARCH</button>
                                        <button type="reset" class="btn btn-secondary">Cancel</button>
                                    </div>
                                </div>
                            </form>

                            <div class="mt-4" id="summaryResultDiv" style="display:none;">
                                <table id="summaryTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Trainee</th>
                                            <th>Total Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<!-- All scripts included directly -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script>
$(function(){
    $("#tabs").tabs({activate:function(event,ui){
        let id = ui.newPanel.attr('id');
        if(id==='tabs-1') $('#searchResultDiv').hide();
        if(id==='tabs-2') $('#exceptionResultDiv').hide();
        if(id==='tabs-3') $('#summaryResultDiv').hide();
    }});
    $(".datepicker").datepicker({ dateFormat:'dd/mm/yy' });

    // Dynamic Departments
    $('#module, #module_exp, #module_summary').change(function(){
        let mod = $(this).val();
        let tgt = $(this).attr('id')==='module'?'#dpt':'';
        if(mod!=0 && tgt){
            $.get('{{ route("admin.reports.departments") }}',{module:mod},function(data){
                $(tgt).empty().append('<option value="0">Please Select..</option>');
                $.each(data,function(i,d){ $(tgt).append('<option value="'+d.value+'">'+d.text+'</option>'); });
            });
        }
    });

    // Autocomplete for trainees
    function bindSearch(box, hid){ $(box).keyup(function(){
        let kw=$(this).val(); if(kw.length>=2){
            $.get('{{ route("admin.reports.trainees") }}',{keyword:kw},function(data){
                let html=''; $.each(data,function(i,u){
                    html+='<div class="suggestion-item" onclick="$(\''+hid+'\').val('+u.id+'); $(box).val(\''+u.name+'\'); $(box).next().hide();">'+u.name+'</div>';
                });
                $(box).next().html(html).show();
            });
        }else $(box).next().hide();
    });}
    bindSearch('#search-box','#user_id');
    bindSearch('#search-box2','#user_id_exp');
    bindSearch('#search-box3','#user_id_summary');

    $(document).click(function(e){if(!$(e.target).closest('.frmSearch').length) $('.suggestion-item').parent().hide();});
});

// Functions to fetch reports
function searchReports(){ 
    let form=$('#searchForm').serialize();
    $.post('{{ route("admin.reports.search") }}',form,function(res){
        $('#searchResultDiv').show(); 
        if($.fn.DataTable.isDataTable('#searchTable')) $('#searchTable').DataTable().destroy();
        let html=''; 
        $.each(res,function(i,r){ 
            html+='<tr>'+
            '<td>'+(i+1)+'</td>'+
            '<td>'+(r.user_name||'N/A')+'</td>'+
            '<td>'+(r.date||'N/A')+'</td>'+
            '<td>'+(r.from_time||'N/A')+'</td>'+
            '<td>'+(r.to_time||'N/A')+'</td>'+
            '<td>'+(r.hours||0)+'</td>'+
            '<td>'+(r.involvement||'N/A')+'</td>'+
            '<td>'+(r.hospital_name||'N/A')+'</td>'+
            '<td>'+(r.department_name||'N/A')+'</td>'+
            '</tr>'; 
        });
        $('#searchTable tbody').html(html); 
        $('#searchTable').DataTable({dom:'Bfrtip',buttons:['excel','pdf']});
    });
}

function getExceptionReports(){ 
    let form=$('#exceptionForm').serialize();
    $.post('{{ route("admin.reports.exception") }}',form,function(res){
        $('#exceptionResultDiv').show(); 
        if($.fn.DataTable.isDataTable('#exceptionTable')) $('#exceptionTable').DataTable().destroy();
        let html='',total=0; 
        $.each(res.data,function(i,r){ 
            html+='<tr>'+
            '<td>'+(i+1)+'</td>'+
            '<td>'+(r.user_name||'N/A')+'</td>'+
            '<td>'+(r.date||'N/A')+'</td>'+
            '<td>'+(r.from_time||'N/A')+'</td>'+
            '<td>'+(r.to_time||'N/A')+'</td>'+
            '<td>'+(r.hours||0)+'</td>'+
            '<td>'+(r.involvement||'N/A')+'</td>'+
            '<td>'+(r.hospital_name||'N/A')+'</td>'+
            '<td>'+(r.department_name||'N/A')+'</td>'+
            '</tr>';
            total+=parseFloat(r.hours||0);
        });
        $('#exceptionTable tbody').html(html); 
        $('#exceptionTable').DataTable({dom:'Bfrtip', buttons:['excel','pdf'], footerCallback:function(){ $(this.api().column(5).footer()).html('Total hours: '+total.toFixed(2)); }});
    });
}

function getSummaryReports(){ 
    let form=$('#summaryForm').serialize();
    $.post('{{ route("admin.reports.summary") }}',form,function(res){
        $('#summaryResultDiv').show(); 
        if($.fn.DataTable.isDataTable('#summaryTable')) $('#summaryTable').DataTable().destroy();
        let html=''; 
        $.each(res.data,function(i,r){ 
            html+='<tr><td>'+(r.user_name||'N/A')+'</td><td>'+(r.total_hours||0)+'</td></tr>'; 
        });
        $('#summaryTable tbody').html(html); 
        $('#summaryTable').DataTable({dom:'Bfrtip',buttons:['excel','pdf'],order:[[1,'desc']]});
    });
}
</script>

<style>
#tabs{margin-top:20px;}
.frmSearch{position:relative;}
#suggesstion-box,#suggesstion-box2,#suggesstion-box3{position:absolute;background:white;border:1px solid #ddd;width:100%;max-height:200px;overflow-y:auto;z-index:1000;}
.suggestion-item{padding:10px;cursor:pointer;border-bottom:1px solid #eee;}
.suggestion-item:hover{background:#f0f0f0;}
</style>
@endpush
