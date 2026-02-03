<?php

namespace App\Http\Controllers\CVBuilder;
use App\Http\Controllers\Controller;
use App\Models\CvTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{

    public function index()
    {
        $templates = CvTemplate::where('user_id', Auth::id())
            ->orWhere('is_default', true)
            ->latest()
            ->get();
        
        return view('cvbuilder.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('cvbuilder.templates.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'design_html' => 'required|string',
            'is_default' => 'boolean'
        ]);

        $template = CvTemplate::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'design_html' => $request->design_html,
            'is_default' => $request->has('is_default')
        ]);

        if ($request->has('is_default')) {
            CvTemplate::where('user_id', Auth::id())
                ->where('id', '!=', $template->id)
                ->update(['is_default' => false]);
        }

        return redirect()->route('templates.index')
            ->with('success', 'Template created successfully!');
    }

    public function edit(CvTemplate $template)
    {
        return view('cvbuilder.templates.edit', compact('template'));
    }

    public function update(Request $request, CvTemplate $template)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'design_html' => 'required|string',
        'is_default' => 'boolean'
    ]);

    $template->update([
        'name' => $request->name,
        'design_html' => $request->design_html,
        'is_default' => $request->has('is_default')
    ]);

    if ($request->has('is_default')) {
        CvTemplate::where('user_id', Auth::id())
            ->where('id', '!=', $template->id)
            ->update(['is_default' => false]);
    }

    return redirect()->route('templates.index')
        ->with('success', 'Template updated successfully!');
}

    public function destroy($id)
    {
        $template = CvTemplate::find($id);
        $template->delete();

        return redirect()->route('templates.index')
            ->with('success', 'Template deleted successfully!');
    }
    
    public function setDefault(CvTemplate $template)
    {
        CvTemplate::where('user_id', Auth::id())
            ->update(['is_default' => false]);
        $template->update(['is_default' => true]);
        
        return redirect()->route('templates.index')
            ->with('success', 'Template set as default!');
    }
}