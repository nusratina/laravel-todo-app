<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $projects = $request->user()->projects()
            ->withCount([
                'tasks',
                'tasks as completed_tasks_count' => fn($query) => $query->where('status', 'completed'),
                'tasks as pending_tasks_count' => fn($query) => $query->where('status', 'pending'),
            ])
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string',
        ]);

        $request->user()->projects()->create($validated);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Request $request, Project $project)
    {
        $this->authorize('view', $project);

        $query = $project->tasks();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('due_date')) {
            match ($request->due_date) {
                'today' => $query->whereDate('due_date', now()->today()),
                'this_week' => $query->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()]),
                'overdue' => $query->where('due_date', '<', now()->today())->where('status', 'pending'),
                default => null,
            };
        }

        $tasks = $query->latest()->get();

        return view('projects.show', compact('project', 'tasks'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}