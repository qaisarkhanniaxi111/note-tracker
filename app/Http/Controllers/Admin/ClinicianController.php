<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Clinician\CreateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClinicianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::clinicians()->get();

        if ($request->ajax()) {

            return Datatables::of($users)
                ->addIndexColumn()
                ->addColumn('name', function($row){
                    return Str::limit($row->name, 20);
                })
                ->addColumn('email', function($row){
                    return Str::limit($row->email, 20);
                })
                ->addColumn('status', function($row){
                    $status = null;

                    if ($row->email_verified_at == null) {
                        $status = '<span class="badge badge-danger"><i class="fas fa-ban"></i></span>';
                    }
                    else if ($row->email_verified_at != null) {
                        $status = '<span class="badge badge-success"><i class="fa-solid fa-check"></i></span>';
                    }

                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" title="Show" class="btn btn-sm btn-success view showButton"> <i class="fas fa-eye"></i> </a>
                    <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" title="Edit" class="btn btn-sm btn-primary edit editButton"> <i class="fas fa-edit"></i> </a>
                    <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" title="Delete" class="btn btn-sm btn-danger del deleteButton"> <i class="fas fa-trash"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('admin.clinicians.index');
    }

    /**
     * Store the specified resource.
     */

    public function store(CreateRequest $request)
    {

        if ($request->clinician_id) {

            $clinician = User::find($request->clinician_id);

            $clinicians = User::where('email', '!=', $clinician->email)->get();

            $clinicianEmails = $clinicians->map(function($clinician) {
                return $clinician->email;
            })->toArray();

            if (! $clinician) {
                return response()->json([
                    'error' => config('error.404_show')
                ], 404);

            }

            else if (in_array($request->email, $clinicianEmails)) {
                return response()->json([
                    'error' => 'Email already taken'
                ], 404);
            }

            try {
                $clinician->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'email_verified_at' => $request->status == 1 ? now() : null,
                ]);
            }
            catch(\Exception $ex) {
                return response()->json([
                    'error' => 'Something went wrong, the error is '. $ex->getMessage()
                ], 401);
            }


        }
        else {

            $clinician = User::where('email', $request->email)->first();

            if ($clinician) {
                return response()->json([
                    'error' => 'email already taken'
                ], 422);
            }

            try {

                User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'email_verified_at' => $request->status == 1 ? now() : null,
                    'password' => Hash::make(config('notetracker.clinician.default_password'))
                ]);

            }
            catch(\Exception $ex) {
                return response()->json([
                    'error' => 'Something went wrong, the error is '. $ex->getMessage()
                ], 401);
            }
        }

        return response()->json([
            'message' => 'Clinician saved successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $clinician = User::find($id);

        if (! $clinician) {
            return response()->json([
                'error' => config('error.404_show')
            ], 404);
        }

        return response()->json([
            'clinician' => [
                'id' => $clinician->id,
                'name' => $clinician->name,
                'email' => $clinician->email,
                'status' => $clinician->email_verified_at != null ? '<span class="badge badge-success"><i class="fa-solid fa-check"></i></span>': '<span class="badge badge-danger"><i class="fas fa-ban"></i></span>' ,
            ]
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $clinician = User::find($id);

        if (! $clinician) {
            return response()->json([
                'error' => config('error.404_show')
            ], 404);
        }

        return response()->json([
            'clinician' => [
                'id' => $clinician->id,
                'name' => $clinician->name,
                'email' => $clinician->email,
                'status' => $clinician->email_verified_at != null ? 1: 0,
            ]
        ], 201);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $clinician = User::find($id);

        if (! $clinician) {
            return response()->json([
                'error' => config('error.404_show')
            ], 404);
        }

        try {

            if (count($clinician->locations) > 0) {
                return response()->json([
                    'error' => 'Clinician is linked with location, so first remove the clinician from the location and perform the action'
                ], 401);
            }

            if (count($clinician->notes) > 0) {
                return response()->json([
                    'error' => 'Clinician is linked with note, so first remove the clinician from the note and perform the action'
                ], 401);
            }

            $clinician->delete();
        }
        catch(\Exception $ex) {
            return response()->json([
                'error' => 'Something went wrong, the error is '. $ex->getMessage()
            ], 401);
        }

        return response()->json([
            'message' => 'Clinician removed successfully'
        ], 201);
    }

    public function loadActiveClinicians()
    {
        $clinicians = User::clinicians()->active()->get();

        return response()->json([
            'clinicians' => $clinicians
        ], 201);
    }
}
