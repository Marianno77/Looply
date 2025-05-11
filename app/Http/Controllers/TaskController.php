<?php

namespace App\Http\Controllers;

use Spatie\Holidays\Holidays;
use App\Models\Task;
use App\Models\TaskInstance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tasks = auth()->user()->tasks;
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'repeat_type' => 'required|string|in:daily,weekly,monthly',
            'repeat_interval' => 'required|integer|min:1',
            'exclude_days' => 'nullable|array',
            'exclude_days.*' => 'in:0,1,2,3,4,5,6',
            'exclude_holidays' => 'boolean',
            'start_date'  => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'completed' => 'boolean',
        ]);

        if(isset($validatedData['exclude_days'])){
            $validatedData['exclude_days'] = json_encode($validatedData['exclude_days']);
        }

        $task = auth()->user()->tasks()->create($validatedData);

        $exclude_days = isset($validatedData['exclude_days']) ? json_decode($validatedData['exclude_days']) : [];
        $start = Carbon::parse($task->start_date);
        $end = $task->end_date
            ? Carbon::parse($task->end_date)
            : $start->copy()->addDays(60);
        $current = $start->copy();
        $holidays = Holidays::for('pl',$current->year)->get();

        while ($current->lte($end)){
            $dayOfWeek = $current->dayOfWeek;

            if($current->year !== Carbon::parse($holidays[0]['date'])->year){
                $holidays = Holidays::for('pl',$current->year)->get();
            }

            if(in_array($dayOfWeek, $exclude_days)){
                $current->addDay();
                continue;
            }

            foreach ($holidays as $holiday){
                if(Carbon::parse($holiday['date'])->isSameDay($current) && $task->exclude_holidays == 0){
                    $current->addDay();
                    continue 2;
                }
            }

            $user_id = auth()->id();
            if($user_id) {
            TaskInstance::create([
                'task_id' => $task->id,
                'user_id' => $user_id,
                'scheduled_for' => $current->format('Y-m-d'),
                'status' => 'pending',
            ]);
            } else {
                throw new \Exception('Nie można utworzyć instancji zadania – brak użytkownika.');
            }

            switch ($task->repeat_type){
                case 'daily':
                    $current->addDay($task->repeat_interval);
                    break;
                case 'weekly':
                    $current->addWeek($task->repeat_interval);
                    break;
                case 'monthly':
                    $current->addMonth($task->repeat_interval);
                    break;
                default:
                    $current->addDay();
            }
        }

        return redirect()->back()->with('success', 'Zadanie dodane!');
    }

    public function update(Request $request, $id)
    {
        $task = auth()->user()->tasks()->findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'repeat_type' => 'required|string|in:daily,weekly,monthly',
            'repeat_interval' => 'required|integer|min:1',
            'exclude_days' => 'nullable|array',
            'exclude_days.*' => 'in:0,1,2,3,4,5,6',
            'exclude_holidays' => 'boolean',
            'start_date'  => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'completed' => 'boolean',
        ]);

        if(isset($validatedData['exclude_days'])){
            $validatedData['exclude_days'] = json_encode($validatedData['exclude_days']);
        }
        $task->update($validatedData);

        return redirect()->back()->with('success', 'Zadanie zaktualizowane!');
    }

    public function destroy($id)
    {
        $task = auth()->user()->tasks()->findOrFail($id);

        $task->delete();

        return redirect()->back()->with('success', 'Zadanie usunięte!');
    }
}
