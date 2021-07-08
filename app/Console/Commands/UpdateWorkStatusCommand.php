<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Enums\WorkStatus;
use App\Models\Work;
use App\Http\Services\WorkService;

class UpdateWorkStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'work:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update work status';
    protected $workService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(WorkService $workService)
    {
        parent::__construct();
        $this->workService = $workService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $works = Work::where('status', '<>', WorkStatus::FINISH)->get();
        foreach($works as $work){
            $this->workService->setStatusApplication($work);
        }
    }
}
