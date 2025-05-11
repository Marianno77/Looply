<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskInstance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultsController extends Controller
{
    public function show()
    {
        $user = Auth::id();
        $tasks = Task::where('user_id', '=', $user)->get();

        $doneTask = TaskInstance::where('user_id', '=', $user)
            ->where('status', '=', 'done')
            ->whereNotNull('completed_at')
            ->selectRaw('YEAR(completed_at) as year, MONTH(completed_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $months = [];
        $taskCounts = [];

        foreach ($doneTask as $task) {
            $months[] = Carbon::create($task->year, $task->month, 1)->format('F Y');
            $taskCounts[] = $task->count;
        }

        /*
        $tasks = auth()->user()->tasks()
                ->withCount([
                    'instances as done_count' => function ($query) {
                        $query->where('status', 'done');
                    },
                    'instances as not_finished_count' => function ($query) {
                        $query->where('status', 'not finished');
                    },
                ])->get();
        */
        return view('results.results', compact('tasks', 'taskCounts', 'months'));
    }
}
