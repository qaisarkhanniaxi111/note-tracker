<?php

namespace App\Http\Controllers\Clinician;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clinician\Note\CreateRequest;
use App\Mail\NoteStatusMail;
use App\Models\ErrorType;
use App\Models\Location;
use App\Models\Note;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DataTables;
use Illuminate\Support\Facades\Mail;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $locations = Location::with(['clinicians'])->get();
        $clinicians = User::with(['locations'])->clinicians()->active()->get();

        $notes = Note::with(['location', 'clinician', 'patient', 'errorType'])->where('clinician_id', $user->id)->get();

        if ($request->ajax()) {

            if ($request->location) {
                $notes = Note::with(['location', 'clinician', 'patient', 'errorType'])->where('location_id', $request->location)->where('clinician_id', $user->id)->get();
            }

            if ($request->clinician) {
                $notes = Note::with(['location', 'clinician', 'patient', 'errorType'])->where('clinician_id', $request->clinician)->where('clinician_id', $user->id)->get();
            }

            if ($request->status) {
                $notes = Note::with(['location', 'clinician', 'patient', 'errorType'])->where('status', $request->status)->where('clinician_id', $user->id)->get();
            }

            elseif ($request->status === null) {
                $notes = Note::with(['location', 'clinician', 'patient', 'errorType'])->where('clinician_id', $user->id)->get();
            }

            elseif ($request->status == Note::NOT_FIXED) {
                $notes = Note::with(['location', 'clinician', 'patient', 'errorType'])->where('status', $request->status)->where('clinician_id', $user->id)->get();
            }

            return Datatables::of($notes)
                ->addIndexColumn()

                ->addColumn('date_of_service', function($row){
                    $dateOfService = null;

                    if ($row->date_of_service) {
                        $dateOfService = date('m-d-Y', strtotime($row->date_of_service));
                    }
                    return $dateOfService;
                })
                ->addColumn('location', function($row){
                    $locationName = null;

                    if ($row->location) {
                        $locationName = $row->location->name;
                    }
                    return Str::limit($locationName, 20);
                })
                ->addColumn('patient', function($row){
                    $patientName = null;

                    if ($row->patient) {
                        $patientName = $row->patient->name;
                    }
                    return Str::limit($patientName, 20);
                })
                ->addColumn('error_type', function($row){
                    $errorTypeName = null;

                    if ($row->errorType) {
                        $errorTypeName = $row->errorType->name;
                    }
                    return Str::limit($errorTypeName, 20);
                })
                ->addColumn('status', function($row){

                    $status = $this->fetchStatusName($row->status);

                    return $status;
                })
                ->addColumn('comment', function($row){
                    return Str::limit(strip_tags($row->comment), 20);
                })
                ->addColumn('status_reason', function($row){
                    return Str::limit($row->status_reason, 20);
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" title="Edit" class="btn btn-sm btn-primary edit editButton"> <i class="fas fa-edit"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['action', 'status', 'checkbox'])
                ->make(true);
        }

        return view('clinicians.dashboard', compact('locations', 'clinicians'));
    }


    public function edit(string $id)
    {
        $note = Note::find($id);

        if (! $note) {
            return response()->json([
                'error' => 'Unable to find the note, something went wrong, please choose the correct value from the dropdown'
            ], 404);
        }

        return response()->json([
            'note' => $note
        ], 201);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        if ($request->note_id) {

            $note = Note::find($request->note_id);
            $noteOldStatus = $note->status;

            if (! $note) {
                return response()->json([
                    'error' => 'Unable to find the note, something went wrong, please choose the correct value from the dropdown'
                ], 404);
            }

            try {

                if ($request->note_status == Note::UNFOUNDED) {
                    $note->update([
                        'status' => $request->note_status,
                        'status_reason' => $request->status_reason
                    ]);
                }
                else {
                    $note->update([
                        'status' => $request->note_status,
                        'status_reason' => ''
                    ]);
                }

                $this->sendStatusUpdateMail($request->note_status, $noteOldStatus, $note);
                $notFixedCounts = Note::where('clinician_id', auth()->id())->where('status', Note::NOT_FIXED)->count();


            }
            catch(\Exception $ex) {
                return response()->json([
                    'error' => $ex
                ], 401);
            }

            return response()->json([
                'message' => 'Note saved successfully',
                'not_fixed_note_counts' => $notFixedCounts
            ], 201);
        }

    }

    public function sendStatusUpdateMail($requestStatus, $noteStatus, $note)
    {
        $admin = User::admins()->first();
        $status = $note->status;

        if ($status == Note::NOT_FIXED) {
            $status = 'Not Fixed';
        }
        else if ($status == Note::FIXED) {
            $status = 'Fixed';
        }
        else if ($status == Note::CONTACT_ME) {
            $status = 'Contact me';
        }
        else if ($status == Note::UNFOUNDED) {
            $status = 'Unfounded';
        }

        $data = [
            'clinician_name' => $note->clinician ? $note->clinician->name: '',
            'patient' => $note->patient ? $note->patient->name: '',
            'date_of_service' => date('m/d/Y', strtotime($note->date_of_service)),
            'note_status' => $status
        ];

        if ($requestStatus != $noteStatus) {
            Mail::to($admin->email)->send(new NoteStatusMail($data));
        }
    }

    public function fetchStatusName($status)
    {
        if ($status == Note::NOT_FIXED) {
            $status = '<span class="badge badge-danger" style="background: red; color:white">Not Fixed</span>';
        }
        else if ($status == Note::FIXED) {
            $status = '<span class="badge badge-success" style="background: green; color:white">Fixed</span>';
        }
        else if ($status == Note::CONTACT_ME) {
            $status = '<span class="badge badge-info" style="background: navy; color:white">Contact me</span>';
        }
        else if ($status == Note::UNFOUNDED) {
            $status = '<span class="badge badge-warning" style="background: #BFF31F; color:black">Unfounded</span>';
        }

        return $status;
    }

}
