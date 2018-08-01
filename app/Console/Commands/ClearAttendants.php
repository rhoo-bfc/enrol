<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearAttendants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendants:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear attendant sessions';

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
     * @return mixed
     */
    public function handle()
    {
        $clearedSessions   = \App\Models\Session::clearAllSessions();
        $closedAssignments = \App\Models\Session::clearClosedAssignments();
        
        $this->info( 'Cleared Sessions => '   . $clearedSessions );
        $this->info( 'Closed Assignments => ' . $closedAssignments );
    }
}
