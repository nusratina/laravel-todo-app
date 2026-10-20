<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projects Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Create Project Form -->
        <div class="p-6 bg-white shadow rounded-lg">
            <h3 class="text-lg font-bold mb-4">Create New Project</h3>
            <form action="{{ route('projects.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-medium text-sm text-gray-700">Project Name</label>
                    <input type="text" name="name" maxlength="120" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block font-medium text-sm text-gray-700">Description (Optional)</label>
                    <textarea name="description" class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Create Project</button>
            </form>
        </div>

        <!-- Project Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($projects as $project)
                <div class="p-6 bg-white shadow rounded-lg flex flex-col justify-between">
                    <div>
                        <h4 class="text-xl font-bold text-gray-900">
                            <a href="{{ route('projects.show', $project) }}" class="hover:underline text-blue-600">
                                {{ $project->name }}
                            </a>
                        </h4>
                        <p class="text-gray-600 text-sm mt-1">{{ $project->description ?? 'No description provided.' }}</p>
                    </div>
                    <div class="mt-4 pt-4 border-t text-sm text-gray-500 space-y-1">
                        <div>Total Tasks: <span class="font-bold">{{ $project->tasks_count }}</span></div>
                        <div>Completed: <span class="text-green-600 font-bold">{{ $project->completed_tasks_count }}</span></div>
                        <div>Pending: <span class="text-yellow-600 font-bold">{{ $project->pending_tasks_count }}</span></div>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="mt-2" onsubmit="return confirm('Delete this project and all its tasks?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs">Delete Project</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No projects found. Create one above to get started!</p>
            @endforelse
        </div>
    </div>
</x-app-layout>