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
            'title' => 'Mail from ItSolutionStuff.com',
            'body' => 'This is for testing email using smtp.'
        ]; 
        Mail::to('masadi.com@gmail.com')->send(new KirimMail($mailData));
    }
}
