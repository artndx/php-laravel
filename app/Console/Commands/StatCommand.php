<?php

namespace App\Console\Commands;


use App\Mail\StatMail;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;

class StatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $commentCount = Comment::whereDate('created_at', Carbon::today())->count();
        Mail::to('artur.stepanyan.03@mail.ru')->send(new StatMail($commentCount));
    }
}
