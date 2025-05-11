<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\TaskInstance;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\Holidays\Holidays;

class TaskInstanceCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task-instance-create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks and adds missing task instances';

    /**
     * Execute the console command.
     */

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $tasks = Task::all();

        foreach ($tasks as $task) {
            $lastInstance = $task->instance()->orderBy('scheduled_for', 'desc')->first();
            $current = $lastInstance ? Carbon::parse($lastInstance->scheduled_for) : Carbon::now();
            if($task->end_date && Carbon::parse($task->end_date)->greaterThanOrEqualTo($current->startOfDay())){
                return;
            }
            $end = $current->copy()->addDays(60);


            $holidays = Holidays::for('pl',$current->year)->get();
            $exclude_days = is_array($task->exclude_days)
                ? $task->exclude_days
                : json_decode($task->exclude_days ?? '[]', true);

            while ($current->lte($end)) {
                if ($current->year !== Carbon::parse($holidays[0]['date'])->year) {
                    $holidays = Holidays::for('pl',$current->year)->get();
                }

                if (in_array($current->dayOfWeek, $exclude_days)) {
                    $current->addDay();
                    continue;
                }

                foreach ($holidays as $holiday) {
                    if (Carbon::parse($holiday['date'])->isSameDay($current) && $task->exclude_holidays = 0) {
                        $current->addDay();
                        continue 2;
                    }
                }

                TaskInstance::updateOrCreate(
                    [
                        'task_id' => $task->id,
                        'user_id' => $task->user_id,
                        'scheduled_for' => $current->format('Y-m-d'),
                    ],
                    ['status' => 'pending']
                );

                switch ($task->repeat_type) {
                    case 'daily':
                        $current->addDay($task->repeat_interval);
                        break;
                    case 'weekly':
                        $current->addWeek($task->repeat_interval);
                        break;
                    case 'monthly':
                        $current->addMonths($task->repeat_interval);
                        break;
                    default:
                        $current->addDay();
                }
            }
        }
        $this->info('Task Instances Created');
    }
}
