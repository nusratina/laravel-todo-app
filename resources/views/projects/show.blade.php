<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Project: {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Add Task Form -->
        <div class="p-6 bg-white shadow rounded-lg">
            <h3 class="text-lg font-bold mb-4">Add Task to {{ $project->name }}</h3>
            <form action="{{ route('tasks.store', $project) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                <div class="col-span-2">
                    <label class="block font-medium text-sm text-gray-700">Task Title</label>
                    <input type="text" name="title" maxlength="160" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block font-medium text-sm text-gray-700">Priority</label>
                    <select name="priority" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium text-sm text-gray-700">Due Date</label>
                    <input type="date" name="due_date" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="col-span-2">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md">Add Task</button>
                </div>
            </form>
        </div>

        <!-- Filter Options -->
        <div class="p-4 bg-gray-100 rounded-lg flex flex-wrap items-center justify-between gap-4">
            <form method="GET" class="flex items-center space-x-4">
                <div>
                    <label class="text-xs font-bold text-gray-600 uppercase">Status:</label>
                    <select name="status" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-md">
                        <option value="">All</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 uppercase">Due Date:</label>
                    <select name="due_date" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-md">
                        <option value="">All Dates</option>
                        <option value="today" {{ request('due_date') === 'today' ? 'selected' : '' }}>Due Today</option>
                        <option value="this_week" {{ request('due_date') === 'this_week' ? 'selected' : '' }}>Due This Week</option>
                        <option value="overdue" {{ request('due_date') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>
                <a href="{{ route('projects.show', $project) }}" class="text-xs text-blue-600 underline">Reset Filters</a>
            </form>
        </div>

        <!-- Tasks List -->
        <div class="bg-white shadow rounded-lg divide-y">
            @forelse($tasks as $task)
                <div class="p-4 flex items-center justify-between">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2">
                            <span class="font-bold {{ $task->status === 'completed' ? 'line-through text-gray-400' : 'text-gray-900' }}">
                                {{ $task->title }}
                            </span>
                            <span class="px-2 py-0.5 text-xs rounded uppercase font-bold 
                                {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ $task->priority }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-500">
                            Due: {{ $task->due_date ? $task->due_date->format('Y-m-d') : 'No due date' }}
                            @if($task->completed_at)
                                | Completed on {{ $task->completed_at->format('Y-m-d H:i') }}
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <!-- Toggle Status Form -->
                        <form action="{{ route('tasks.update', $task) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="title" value="{{ $task->title }}">
                            <input type="hidden" name="priority" value="{{ $task->priority }}">
                            <input type="hidden" name="status" value="{{ $task->status === 'completed' ? 'pending' : 'completed' }}">
                            <button type="submit" class="px-3 py-1 text-xs rounded border {{ $task->status === 'completed' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                Mark as {{ $task->status === 'completed' ? 'Pending' : 'Completed' }}
                            </button>
                        </form>

                        <!-- Delete Task Form -->
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 text-xs rounded bg-red-100 text-red-800 hover:bg-red-200">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">No tasks found for this project matching your criteria.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>