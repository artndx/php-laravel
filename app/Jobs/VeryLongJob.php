<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\MailNewComment;
use Illuminate\Support\Facades\Mail;
use App\Models\Article;

class VeryLongJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Article $article)
    {
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to('artur.stepanyan.03@mail.ru')->send(new MailNewComment($this->article));
    }
}
