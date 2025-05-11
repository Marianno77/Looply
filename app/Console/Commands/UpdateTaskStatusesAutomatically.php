<?php

namespace App\Console\Commands;

use App\Models\TaskInstance;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateTaskStatusesAutomatically extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates task statuses to "not finished" if the scheduled date has passed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tasks = TaskInstance::where('status', '==', 'pending')
                                ->whereDate('scheduled_for', '<', Carbon::today())
                                ->get();

        foreach ($tasks as $task) {
            $task->status = 'not finished';
            $task->save();
            $this->info("Task status updated successfully! task id: {$task->id}");
        }
    }
}
