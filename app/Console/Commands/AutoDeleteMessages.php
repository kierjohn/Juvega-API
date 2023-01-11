<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Acme\Services\Messages;

class AutoDeleteMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Delete Message';

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
        $data = [];

        $services = new Messages;
        $services->deletebytimer($data);
        sleep(5);
        $services->deletebytimer($data);
        sleep(5);
        $services->deletebytimer($data);
        sleep(5);
        $services->deletebytimer($data);
        sleep(5);
        $services->deletebytimer($data);
        sleep(5);
        $services->deletebytimer($data);
        sleep(5);
        $services->deletebytimer($data);
        sleep(5);
        $services->deletebytimer($data);
        sleep(5);
        $services->deletebytimer($data);
        sleep(5);
        $services->deletebytimer($data);
        sleep(5);
        $services->deletebytimer($data);
        sleep(5);
        $services->deletebytimer($data);

        $this->info("Your Job is being processed");
    }
}
