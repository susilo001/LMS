<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use Inertia\Inertia;

class ModuleController extends Controller
{
    public function index()
    {
        return inertia('Teacher/Modules/Index');
    }

    public function create()
    {
        return Inertia::render('Module/Create');
    }

    public function show(Module $module)
    {
        return Inertia::render('Module/Show', [
            'module' => $module
        ]);
    }

    public function edit(Module $module)
    {
        return Inertia::render('Module/Edit', [
            'module' => $module
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'course_id' => 'required',
            'description' => 'required',
            'content' => 'nullable|file|max:2048',
        ]);

        if ($request->hasFile('content')) {
            $content = $request->file('content')->store('content', 'public');
        }

        Module::create([
            'name' => $request->name,
            'course_id' => $request->course_id,
            'description' => $request->description,
            'content' => $content,
            'order_number' => rand(1, 10),
        ]);

        return redirect()->back()->with([
            'message' => 'Module created successfully',
            'status' => 'Success',
        ]);
    }

    public function update(Request $request, Module $module)
    {
        $request->validate([
            'name' => 'nullable',
            'course_id' => 'nullable',
            'description' => 'nullable',
            'content' => 'nullable|file|max:2048',
        ]);

        if ($request->hasFile('content')) {
            $content = $request->file('content')->store('content', 'public');
        }

        $module->update($request->all() + ['content' => $content]);

        return redirect()->back()->with([
            'message' => 'Module updated successfully',
            'status' => 'Success',
        ]);
    }

    public function destroy(Request $request, Module $module)
    {
        $module->delete();

        return redirect()->back()->with([
            'message' => 'Module deleted successfully',
            'status' => 'Success',
        ]);
    }
}
