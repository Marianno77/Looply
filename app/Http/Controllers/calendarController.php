<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskInstance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class calendarController extends Controller
{
    public function index(Request $request) {
        $monthInput = $request->input('month');

        $currentMonth = $monthInput
            ? Carbon::parse($monthInput)->startOfMonth()
            : Carbon::now()->startOfMonth();

        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        $startday = $startOfMonth->dayOfWeek;
        $startday = $startday-1;

        $daysInMonth = collect();

        for ($i = 0; $i < $startday; $i++) {
            $daysInMonth->push(null);
        }

        for ($day = $startOfMonth->copy(); $day->lte($endOfMonth); $day->addDay()) {
            $daysInMonth->push($day->copy());
        }

        $now = Carbon::now();
        $now =  $now->format('Y-m-d');

        $taskInstance = taskInstance::with('task')
                                    ->whereBetween('scheduled_for',[$startOfMonth, $endOfMonth])
                                    ->where('user_id', $request->user()->id)
                                    ->get();

        return view('calendar.calendar', [
            'days' =>  $daysInMonth,
            'currentMonth' =>  $currentMonth,
            'taskInstance' =>  $taskInstance,
            'today' =>  $now,
        ]);
    }
}
