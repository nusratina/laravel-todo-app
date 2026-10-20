<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My To-Do App</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        input[type="text"] { width: 80%; padding: 8px; }
        button { padding: 8px 15px; background: #2563eb; color: white; border: none; cursor: pointer; }
        ul { list-style: none; padding: 0; }
        li { padding: 10px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; }
    </style>
</head>
<body>
    <h1>My To-Do List</h1>

    <form action="/tasks" method="POST" class="form-group">
        @csrf
        <input type="text" name="title" placeholder="Add a new task..." required>
        <button type="submit">Add Task</button>
    </form>

    <ul>
        @foreach($tasks as $task)
            <li>
                <span>{{ $task->title }}</span>
            </li>
        @endforeach
    </ul>
</body>
</html>