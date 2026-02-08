<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CicuWardRound;
use App\Models\GrandWardRound;
use App\Models\DailyWardRound;
use App\Models\DopsTrainee;
use App\Models\User;
use App\Models\Hospital;
use App\Models\Department;
use App\Models\ModalType;
use Carbon\Carbon;
use DB;

class ReportController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::all();
        return view('admin.reports.index', compact('hospitals'));
    }

    // Dynamic department dropdown based on module type
    public function getDepartments(Request $request)
    {
        $module = $request->module;
        
        if ($module == '0' || $module == '') {
            return response()->json([]);
        }
        
        // Get departments based on module_type
        $departments = Department::where('module_type', $module)->active()->get();
        
        $data = $departments->map(function($d) {
            return [
                'value' => $d->id,
                'text' => $d->name . ' (' . $d->module_label . ')'
            ];
        });
        
        return response()->json($data);
    }

    // Trainee autocomplete
    public function getTrainees(Request $request)
    {
        $keyword = $request->keyword;
        $users = User::where('fname', 'like', "%{$keyword}%")
                     ->orWhere('lname', 'like', "%{$keyword}%")
                     ->get();
        
        $data = $users->map(function($u) {
            return [
                'id' => $u->id,
                'name' => $u->fname . ' ' . $u->lname
            ];
        });
        
        return response()->json($data);
    }

    // Main search reports - Updated with ModalType
    public function search(Request $request)
    {
        $userId = $request->user_id ?: null;
        $hospitalId = $request->hospital ?: null;
        $moduleCode = $request->module ?: null;
        $departmentId = $request->dpt ?: null;
        $status = $request->involvement ?: null;

        $sdate = $request->sdate ? Carbon::createFromFormat('d/m/Y', $request->sdate)->format('Y-m-d') : null;
        $edate = $request->edate ? Carbon::createFromFormat('d/m/Y', $request->edate)->format('Y-m-d') : null;

        $data = collect();

        // Get modal types based on selected module and department
        $modalTypesQuery = ModalType::with('department')->active();
        
        if ($moduleCode && $moduleCode != '0') {
            // Get departments for this module
            $departmentIds = Department::where('module_type', $moduleCode)->pluck('id');
            $modalTypesQuery->whereIn('department_id', $departmentIds);
        }
        
        if ($departmentId && $departmentId != '0') {
            $modalTypesQuery->where('department_id', $departmentId);
        }
        
        $modalTypes = $modalTypesQuery->get();
        
        if ($modalTypes->isEmpty()) {
            return response()->json([]);
        }

        // Define model mappings for table names
        $modelMappings = [
            'cicu_ward_rounds' => CicuWardRound::class,
            'grand_ward_rounds' => GrandWardRound::class,
            'daily_ward_rounds' => DailyWardRound::class,
            // 'dops_trainees' => DopsTrainee::class,
        ];

        // Group modal types by table name
        $modalTypesByTable = $modalTypes->groupBy('table_name');

        foreach ($modalTypesByTable as $tableName => $modalTypesGroup) {
            // Check if model exists for this table
            if (!isset($modelMappings[$tableName])) {
                continue;
            }

            $modelClass = $modelMappings[$tableName];
            $modalTypeIds = $modalTypesGroup->pluck('id')->toArray();
            
            $query = $modelClass::query()
                ->with(['user', 'hospital'])
                ->whereIn('modal_type_id', $modalTypeIds);

            // Apply filters
            $query->when($userId, function($q) use ($userId) {
                return $q->where('user_id', $userId);
            })
            ->when($hospitalId, function($q) use ($hospitalId) {
                return $q->where('hospital_id', $hospitalId);
            })
            ->when($status, function($q) use ($status) {
                return $q->where('involvement', $status);
            })
            ->when($sdate, function($q) use ($sdate) {
                return $q->whereDate('date', '>=', $sdate);
            })
            ->when($edate, function($q) use ($edate) {
                return $q->whereDate('date', '<=', $edate);
            });

            $results = $query->get()->map(function($record) use ($modalTypesGroup, $tableName) {
                // Find the modal type for this record
                $modalType = $modalTypesGroup->firstWhere('id', $record->modal_type_id);
                $department = $modalType ? $modalType->department : null;
                
                // Get common fields
                $result = [
                    'modal_type_id' => $record->modal_type_id,
                    'modal_type_name' => $modalType ? $modalType->name : 'N/A',
                    'table_name' => $tableName,
                    'user_name' => ($record->user->fname ?? '') . ' ' . ($record->user->lname ?? ''),
                    'hospital_name' => $record->hospital->name ?? 'N/A',
                    'department_id' => $department ? $department->id : null,
                    'department_name' => $department ? $department->name : 'N/A',
                    'module_type' => $department ? $department->module_type : 'N/A',
                    'module_label' => $department ? $department->module_label : 'N/A',
                    'date' => $record->date ?? '',
                    'hours' => $record->hours ?? 0,
                    'involvement' => $record->involvement ?? 'N/A',
                ];

                // Add table-specific fields
                $this->addTableSpecificFields($result, $record, $tableName);

                return $result;
            });

            $data = $data->merge($results);
        }

        // Sort by date descending
        $data = $data->sortByDesc('date')->values();

        return response()->json($data);
    }

    // Helper method to add table-specific fields
    private function addTableSpecificFields(&$result, $record, $tableName)
    {
        switch ($tableName) {
            case 'cicu_ward_rounds':
                $result['from_time'] = $record->from_time ?? 'N/A';
                $result['to_time'] = $record->to_time ?? 'N/A';
                $result['case_details'] = $record->case_details ?? 'N/A';
                $result['patient_age'] = $record->patient_age ?? 'N/A';
                $result['patient_gender'] = $record->patient_gender ?? 'N/A';
                break;
                
            case 'grand_ward_rounds':
                $result['from_time'] = $record->from_time ?? 'N/A';
                $result['to_time'] = $record->to_time ?? 'N/A';
                $result['topic'] = $record->topic ?? 'N/A';
                $result['presenter'] = $record->presenter ?? 'N/A';
                break;
                
            case 'daily_ward_rounds':
                $result['from_time'] = $record->from_time ?? 'N/A';
                $result['to_time'] = $record->to_time ?? 'N/A';
                $result['cases_reviewed'] = $record->cases_reviewed ?? 'N/A';
                break;
                
            case 'dops_trainees':
                $result['procedure_name'] = $record->procedure_name ?? 'N/A';
                $result['supervisor_name'] = $record->supervisor_name ?? 'N/A';
                $result['procedure_date'] = $record->procedure_date ?? 'N/A';
                $result['difficulty_level'] = $record->difficulty_level ?? 'N/A';
                break;
        }
    }

    // Exception reports
    public function exception(Request $request)
    {
        $userId = $request->user_id_exp ?: null;
        $reportType = $request->report_type ?: 'D';
        $moduleCode = $request->module_exp ?: null;
        $isAbsent = $request->is_absent == 'Y';

        $data = collect();

        // Calculate date range based on report type
        $dateRange = $this->getDateRange($reportType);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        // Get modal types based on selected module
        $modalTypesQuery = ModalType::with('department')->active();
        
        if ($moduleCode && $moduleCode != '0') {
            $departmentIds = Department::where('module_type', $moduleCode)->pluck('id');
            $modalTypesQuery->whereIn('department_id', $departmentIds);
        }
        
        $modalTypes = $modalTypesQuery->get();
        
        if ($modalTypes->isEmpty()) {
            return response()->json(['data' => []]);
        }

        // Group modal types by table name
        $modalTypesByTable = $modalTypes->groupBy('table_name');

        // Define model mappings
        $modelMappings = [
            'cicu_ward_rounds' => CicuWardRound::class,
            'grand_ward_rounds' => GrandWardRound::class,
            'daily_ward_rounds' => DailyWardRound::class,
            // 'dops_trainees' => DopsTrainee::class,
        ];

        foreach ($modalTypesByTable as $tableName => $modalTypesGroup) {
            if (!isset($modelMappings[$tableName])) {
                continue;
            }

            $modelClass = $modelMappings[$tableName];
            $modalTypeIds = $modalTypesGroup->pluck('id')->toArray();
            
            $query = $modelClass::query()
                ->with(['user', 'hospital'])
                ->whereIn('modal_type_id', $modalTypeIds)
                ->whereBetween('date', [$startDate, $endDate]);

            // Apply user filter
            if ($userId) {
                $query->where('user_id', $userId);
            }

            // Apply absent filter
            if ($isAbsent) {
                $query->where(function($q) {
                    $q->whereNull('hours')
                      ->orWhere('hours', '<=', 0)
                      ->orWhereNull('from_time');
                });
            }

            $results = $query->get()->map(function($record) use ($modalTypesGroup, $tableName, $isAbsent) {
                $modalType = $modalTypesGroup->firstWhere('id', $record->modal_type_id);
                $department = $modalType ? $modalType->department : null;
                
                $result = [
                    'modal_type_name' => $modalType ? $modalType->name : 'N/A',
                    'user_name' => ($record->user->fname ?? '') . ' ' . ($record->user->lname ?? ''),
                    'hospital_name' => $record->hospital->name ?? 'N/A',
                    'department_name' => $department ? $department->name : 'N/A',
                    'date' => $record->date ?? '',
                    'hours' => $record->hours ?? 0,
                    'involvement' => $record->involvement ?? 'N/A',
                    'exception_type' => $isAbsent ? 'Absent' : 'Incomplete',
                ];

                // Add time fields if available
                if (in_array($tableName, ['cicu_ward_rounds', 'grand_ward_rounds', 'daily_ward_rounds'])) {
                    $result['from_time'] = $record->from_time ?? 'N/A';
                    $result['to_time'] = $record->to_time ?? 'N/A';
                }

                return $result;
            });

            $data = $data->merge($results);
        }

        return response()->json(['data' => $data->sortByDesc('date')->values()]);
    }

    // Summary reports
    public function summary(Request $request)
    {
        $userId = $request->user_id_summary ?: null;
        $reportType = $request->report_type_summary ?: 'D';
        $moduleCode = $request->module_summary ?: null;

        $data = collect();

        // Calculate date range
        $dateRange = $this->getDateRange($reportType);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        // Get modal types based on selected module
        $modalTypesQuery = ModalType::with('department')->active();
        
        if ($moduleCode && $moduleCode != '0') {
            $departmentIds = Department::where('module_type', $moduleCode)->pluck('id');
            $modalTypesQuery->whereIn('department_id', $departmentIds);
        }
        
        $modalTypes = $modalTypesQuery->get();
        
        if ($modalTypes->isEmpty()) {
            return response()->json(['data' => []]);
        }

        // Group modal types by table name
        $modalTypesByTable = $modalTypes->groupBy('table_name');

        // Define model mappings
        $modelMappings = [
            'cicu_ward_rounds' => CicuWardRound::class,
            'grand_ward_rounds' => GrandWardRound::class,
            'daily_ward_rounds' => DailyWardRound::class,
            // 'dops_trainees' => DopsTrainee::class,
        ];

        foreach ($modalTypesByTable as $tableName => $modalTypesGroup) {
            if (!isset($modelMappings[$tableName])) {
                continue;
            }

            $modelClass = $modelMappings[$tableName];
            $modalTypeIds = $modalTypesGroup->pluck('id')->toArray();
            
            $query = $modelClass::query()
                ->with('user')
                ->whereIn('modal_type_id', $modalTypeIds)
                ->whereBetween('date', [$startDate, $endDate]);

            if ($userId) {
                $query->where('user_id', $userId);
            }

            $results = $query->get()->map(function($record) use ($modalTypesGroup) {
                $modalType = $modalTypesGroup->firstWhere('id', $record->modal_type_id);
                $department = $modalType ? $modalType->department : null;
                
                return [
                    'user_name' => ($record->user->fname ?? '') . ' ' . ($record->user->lname ?? ''),
                    'user_id' => $record->user_id,
                    'modal_type_id' => $record->modal_type_id,
                    'modal_type_name' => $modalType ? $modalType->name : 'N/A',
                    'department_name' => $department ? $department->name : 'N/A',
                    'module_type' => $department ? $department->module_type : 'N/A',
                    'hours' => $record->hours ?? 0,
                    'date' => $record->date ?? '',
                ];
            });

            $data = $data->merge($results);
        }

        // Group by user
        $summary = $data->groupBy('user_id')->map(function($userRecords, $userId) {
            $firstRecord = $userRecords->first();
            
            // Group by modal type within user
            $modalTypeSummary = $userRecords->groupBy('modal_type_name')->map(function($modalRecords, $modalTypeName) {
                return [
                    'modal_type' => $modalTypeName,
                    'hours' => $modalRecords->sum('hours'),
                    'count' => $modalRecords->count()
                ];
            })->values();
            
            return [
                'user_id' => $userId,
                'user_name' => $firstRecord['user_name'],
                'total_hours' => $userRecords->sum('hours'),
                'total_records' => $userRecords->count(),
                'modal_types' => $modalTypeSummary
            ];
        })->values();

        return response()->json(['data' => $summary]);
    }

    // Helper function to get date range based on report type
    private function getDateRange($reportType)
    {
        $today = Carbon::today();
        
        switch ($reportType) {
            case 'D': // Today
                return [
                    'start' => $today->format('Y-m-d'),
                    'end' => $today->format('Y-m-d')
                ];
                
            case 'D1': // Yesterday
                $yesterday = $today->copy()->subDay();
                return [
                    'start' => $yesterday->format('Y-m-d'),
                    'end' => $yesterday->format('Y-m-d')
                ];
                
            case 'W': // This week
                return [
                    'start' => $today->copy()->startOfWeek()->format('Y-m-d'),
                    'end' => $today->copy()->endOfWeek()->format('Y-m-d')
                ];
                
            case 'M': // This month
                return [
                    'start' => $today->copy()->startOfMonth()->format('Y-m-d'),
                    'end' => $today->copy()->endOfMonth()->format('Y-m-d')
                ];
                
            default:
                return [
                    'start' => $today->format('Y-m-d'),
                    'end' => $today->format('Y-m-d')
                ];
        }
    }
}