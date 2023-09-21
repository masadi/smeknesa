<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mails\KirimMail;

class MailController extends Controller
{
    public function index()
    {
        $mailData = [
            'title' => 'Judul Email',
            'body' => 'Body Email.'
        ]; 
        Mail::to('masadi.com@gmail.com')->send(new KirimMail($mailData));
    }
}
