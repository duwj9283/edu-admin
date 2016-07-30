<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{
    public function getIndex()
    {
        return view('welcome');
    }
}
