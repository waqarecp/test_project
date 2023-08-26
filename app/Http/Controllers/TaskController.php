<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all(); // Fetch tasks from the database
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:25',
            'description' => 'required|string',
        ]);

        $task = new Task($validatedData);
        $task->status = 'pending'; // Set default status
        $task->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Task created successfully!',
        ]);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'task_id' => 'required|exists:tasks,id', // Validate the task_id
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $task = Task::findOrFail($validatedData['task_id']); // Find the task by ID
        $task->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Task updated successfully!',
        ]);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:pending,completed',
        ]);

        $task->update(['status' => $validatedData['status']]);

        return redirect()->route('tasks.index')->with('success', 'Task status updated successfully!');
    }

    public function syncTasksFromApi()
    {
        // Fetch tasks from the external API
        $response = Http::get('https://jsonplaceholder.typicode.com/todos');

        if ($response->successful()) {
            $apiTasks = $response->json();

            foreach ($apiTasks as $apiTask) {
                Task::updateOrCreate(
                    ['api_id' => $apiTask['id']],
                    [
                        'title' => $apiTask['title'],
                        'description' => $apiTask['title'], // Use the appropriate field from the API
                        'status' => $apiTask['completed'] ? 'completed' : 'pending',
                    ]
                );
            }

            return redirect()->route('tasks.index')->with('success', 'Tasks synchronized successfully!');
        } else {
            return redirect()->route('tasks.index')->with('error', 'Failed to synchronize tasks from API.');
        }
    }
}
