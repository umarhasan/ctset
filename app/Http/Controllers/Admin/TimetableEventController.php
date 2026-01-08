<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimetableEvent;
use App\Models\TimeTableCategory;
use Illuminate\Http\Request;

class TimetableEventController extends Controller
{
    public function index()
    {
        $events = TimetableEvent::with('category')->latest()->get();
        $categories = TimeTableCategory::all();
        return view('admin.timetable_events.index', compact('events','categories'));
    }

    // AJAX create
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string',
            'category_id'  => 'required|exists:time_table_categories,id',
            'date'         => 'required|date',
            'from_time'    => 'required',
            'to_time'      => 'required',
            'image'        => 'nullable|image',
            'reminder_days'=> 'nullable|integer',
            'description'  => 'nullable|string',
        ]);

        $data['is_superviser'] = $request->has('is_superviser');
        $data['is_trainee']    = $request->has('is_trainee');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        TimetableEvent::create($data);

        return response()->json(['success' => true]);
    }

    // AJAX edit
    public function edit(TimetableEvent $timetable_event)
    {
        return response()->json($timetable_event);
    }

    // AJAX update
    public function update(Request $request, TimetableEvent $timetable_event)
    {
        $data = $request->validate([
            'title'        => 'required|string',
            'category_id'  => 'required|exists:time_table_categories,id',
            'date'         => 'required|date',
            'from_time'    => 'required',
            'to_time'      => 'required',
            'image'        => 'nullable|image',
            'reminder_days'=> 'nullable|integer',
            'description'  => 'nullable|string',
        ]);

        $data['is_superviser'] = $request->has('is_superviser');
        $data['is_trainee']    = $request->has('is_trainee');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $timetable_event->update($data);

        return response()->json(['success' => true]);
    }

    // AJAX delete
    public function destroy(TimetableEvent $timetable_event)
    {
        $timetable_event->delete();
        return response()->json(['success' => true]);
    }
}
