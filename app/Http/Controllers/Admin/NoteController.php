<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Note\CreateRequest;
use App\Models\ErrorType;
use App\Models\Location;
use App\Models\Note;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DataTables;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $locations = Location::with(['clinicians'])->get();
        $clinicians = User::with(['locations'])->clinicians()->active()->get();

        $notes = Note::query()->with(['location', 'clinician', 'patient', 'errorType']);

        if ($request->ajax()) {

            if ($request->location) {
                $notes->where('location_id', $request->location);
            }

            if ($request->clinician) {
                $notes->where('clinician_id', $request->clinician);
            }

            if ($request->status != 'something') {
                $notes->where('status', $request->status);
            }

            if ($request->status == Note::NOT_FIXED) {
                $notes->where('status', $request->status);
            }

            else if ($request->status === 'something') {
                $notes;
            }

            $notes->get();

            return Datatables::of($notes)
                ->addIndexColumn()

                ->addColumn('location', function($row){
                    $locationName = null;

                    if ($row->location) {
                        $locationName = $row->location->name;
                    }
                    return Str::limit($locationName, 20);
                })
                ->addColumn('clinician', function($row){
                    $clinicianName = null;

                    if ($row->clinician) {
                        $clinicianName = $row->clinician->name;
                    }
                    return Str::limit($clinicianName, 20);
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
                ->addColumn('date_of_service', function($row){
                    return date('m-d-Y', strtotime($row->date_of_service));
                })
                ->addColumn('status', function($row){
                    $status = null;

                    if ($row->status == Note::NOT_FIXED) {

                        $status = '<span class="badge badge-danger">Not Fixed</span>';
                    }
                    else if ($row->status == Note::FIXED) {
                        $status = '<span class="badge badge-success">Fixed</span>';
                    }
                    else if ($row->status == Note::CONTACT_ME) {
                        $status = '<span class="badge badge-info">Contact me</span>';
                    }
                    else if ($row->status == Note::UNFOUNDED) {
                        $status = '<span class="badge badge-warning">Unfounded</span>';
                    }

                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('admin.notes.show', $row->id).'" data-toggle="tooltip"  data-id="'.$row->id.'" title="Show" class="btn btn-sm btn-success view showButton"> <i class="fas fa-eye"></i> </a>
                    <a href="'.route('admin.notes.edit', $row->id).'" data-toggle="tooltip"  data-id="'.$row->id.'" title="Edit" class="btn btn-sm btn-primary edit editButton"> <i class="fas fa-edit"></i> </a>
                    <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" title="Delete" class="btn btn-sm btn-danger del deleteButton"> <i class="fas fa-trash"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.notes.index', compact('locations', 'clinicians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locations = Location::with(['clinicians'])->active()->get();
        $clinicians = User::with(['locations'])->clinicians()->active()->get();
        $errorTypes = ErrorType::active()->get();

        return view('admin.notes.create', compact('locations', 'clinicians', 'errorTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $location = Location::find($request->location);
        $clinician = User::find($request->clinician);
        $errorType = ErrorType::find($request->error_type);
        $patient = Patient::firstOrCreate(['name' => $request->patient]);

        if (! $location) {
            return back()->withInput()->withErrors('Unable to find the location, please choose the correct value from the dropdown');
        }

        if (! $clinician) {
            return back()->withInput()->withErrors('Unable to find the clinician, please choose the correct value from the dropdown');
        }

        if (! $errorType) {
            return back()->withInput()->withErrors('Unable to find the errorType, please choose the correct value from the dropdown');
        }

        if (! $patient) {
            return back()->withInput()->withErrors('Unable to process the patient, something went wrong, please contact with administrator to solve this issue');
        }

        if ($request->note_id) {
            $note = Note::find($request->note_id);

            if (! $note) {
                return back()->withInput()->withErrors('Unable to find the note, something went wrong, please choose the correct value from the dropdown');
            }

            $note->update([
                'location_id' => $location->id,
                'clinician_id' => $clinician->id,
                'error_type_id' => $errorType->id,
                'patient_id' => $patient->id,
                'date_of_service' => $request->date_of_service,
                'comment' => $request->comment ? $request->comment: '',
                'status' => $request->note_status,
                'status_reason' => $request->status_reason
            ]);
        }
        else {
            Note::create([
                'location_id' => $location->id,
                'clinician_id' => $clinician->id,
                'error_type_id' => $errorType->id,
                'patient_id' => $patient->id,
                'date_of_service' => $request->date_of_service,
                'comment' => $request->comment ? $request->comment: '',
                'status' => Note::NOT_FIXED
            ]);
        }

       session()->flash('alert-success', 'Note saved successfully');

       return to_route('admin.notes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        return view('admin.notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        $locations = Location::with(['clinicians'])->get();
        $clinicians = User::with(['locations'])->clinicians()->get();
        $errorTypes = ErrorType::active()->get();

        $noteLocation = null;
        $noteClinician = null;
        $noteErrorType = null;
        $notePatientName = null;


        if ($note->location) {
            $noteLocation = $note->location;
        }

        if ($note->clinician) {
            $noteClinician = $note->clinician;
        }

        if ($note->errorType) {
            $noteErrorType = $note->errorType;
        }

        if ($note->patient) {
            $notePatientName = $note->patient->name;
        }

        return view('admin.notes.create', compact('locations', 'clinicians', 'errorTypes', 'note', 'noteLocation', 'noteClinician', 'notePatientName', 'noteErrorType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $note = Note::find($id);

        if (! $note) {
            return response()->json([
                'error' => config('error.404_show')
            ], 404);
        }

        try {
            $note->delete();
        }
        catch(\Exception $ex) {
            return response()->json([
                'error' => 'Something went wrong, the error is '. $ex->getMessage()
            ], 401);
        }

        return response()->json([
            'message' => 'Note removed successfully'
        ], 201);
    }

    public function findPatients(Request $request)
    {
        $patientName = $request->patientName;

        try {
            $patientNames = Patient::search($patientName)->get();
        }
        catch(\Exception $ex) {
            return response()->json([
                'error' => 'Failed'
            ], 401);
        }

        return response()->json([
            'patient_names' => $patientNames
        ], 201);
    }
}
