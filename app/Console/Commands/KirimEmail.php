<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use App\Mail\KirimEmail as SendEmail;

class KirimEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kirim:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $mailData = [
            'title' => 'Judul Email',
            'body' => 'Body Email.'
        ]; 
        Mail::to('masadi.com@gmail.com')->send(new SendEmail($mailData));
        return Command::SUCCESS;
    }
}
