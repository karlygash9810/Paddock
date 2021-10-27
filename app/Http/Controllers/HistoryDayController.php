<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;

class HistoryDayController extends Controller
{
    public function index()
    {
        return History::orderBy('created_at', 'desc')->first();
    }
}
