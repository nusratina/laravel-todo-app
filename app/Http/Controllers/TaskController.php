<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Project $project)
    {
        $this->authorize('view', $project);

        $validated = $request->validate([
            'title' => 'required|string|max:160',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $project->tasks()->create($validated);

        return redirect()->route('projects.show', $project)->with('success', 'Task added successfully.');
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:160',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,completed',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        if ($validated['status'] === 'completed' && $task->status !== 'completed') {
            $validated['completed_at'] = now();
        } elseif ($validated['status'] === 'pending') {
            $validated['completed_at'] = null;
        }

        $task->update($validated);

        return redirect()->route('projects.show', $task->project_id)->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $projectId = $task->project_id;
        $task->delete();

        return redirect()->route('projects.show', $projectId)->with('success', 'Task deleted successfully.');
    }
}
