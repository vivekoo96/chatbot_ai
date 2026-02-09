<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RunQueueWorker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-queue-worker {--timeout=60} {--tries=3}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the queue worker to process jobs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Queue worker started', [
            'timeout' => $this->option('timeout'),
            'tries' => $this->option('tries')
        ]);

        $this->call('queue:work', [
            '--stop-when-empty' => true,
            '--max-time' => $this->option('timeout'),
            '--tries' => $this->option('tries'),
        ]);

        return 0;
    }
}
