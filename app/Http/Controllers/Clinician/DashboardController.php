<?php

namespace App\Http\Controllers\Clinician;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $query = Note::with(['location', 'clinician', 'patient', 'errorType'])->where('clinician_id', $user->id);

        $notes = $query->get();
        $notFixedCounts = $query->where('status', Note::NOT_FIXED)->count();

        return view('clinicians.dashboard', compact('notes', 'notFixedCounts'));
    }
}
