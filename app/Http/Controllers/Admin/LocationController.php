<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Location\CreateRequest;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clinicians = User::clinicians()->active()->get();
        $locations = Location::with(['clinicians'])->get();

        if ($request->ajax()) {

            return Datatables::of($locations)
                ->addIndexColumn()
                ->addColumn('name', function($row){
                    return Str::limit($row->name, 20);
                })
                ->addColumn('email', function($row){
                    return Str::limit($row->email, 20);
                })
                ->addColumn('status', function($row){

                    if ($row->status == Location::NO) {

                        $status = '<label data-id="'.$row->id.'" class="switch-off switch switch-text switch-outline-alt-primary switch-pill form-control-label mr-2">
                                        <input type="checkbox" class="switch-input form-check-input" value="Off">
                                        <span class="switch-label" data-on="On" data-off="Off"></span>
                                        <span class="switch-handle"></span>
                                    </label>';
                    }
                    else if ($row->status == Location::YES) {
                        $status = '<label data-id="'.$row->id.'" class="switch-on switch switch-text switch-outline-alt-primary switch-pill form-control-label mr-2">
                                        <input type="checkbox" class="switch-input form-check-input" value="On" checked="checked">
                                        <span class="switch-label" data-on="On" data-off="Off"></span>
                                        <span class="switch-handle"></span>
                                    </label>';
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

        return view('admin.locations.index', compact('clinicians'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {

        if ($request->location_id) {

            $location = Location::find($request->location_id);
            $locations = Location::where('email', '!=', $location->email)->get();

            $locationEmails = $locations->map(function($location) {
                return $location->email;
            })->toArray();

            if (! $location) {
                return response()->json([
                    'error' => config('error.404_show')
                ], 404);

            }

            else if (in_array($request->email, $locationEmails)) {
                return response()->json([
                    'error' => 'Email already taken'
                ], 404);
            }

            try {
                $location->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'status' => $request->status,
                ]);

                $location->clinicians()->sync($request->clinicians);
            }
            catch(\Exception $ex) {
                return response()->json([
                    'error' => 'Something went wrong, the error is '. $ex->getMessage()
                ], 401);
            }


        }
        else {

            $location = Location::where('email', $request->email)->first();

            if ($location) {
                return response()->json([
                    'error' => 'email already taken'
                ], 422);
            }

            try {

                $location = Location::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'status' => $request->status,
                ]);

                if ($clinicians = $request->clinicians) {
                    $location->clinicians()->attach($clinicians);
                }
            }
            catch(\Exception $ex) {
                return response()->json([
                    'error' => 'Something went wrong, the error is '. $ex->getMessage()
                ], 401);
            }
        }

        return response()->json([
            'message' => 'Location saved successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $location = Location::find($id);

        if (! $location) {
            return response()->json([
                'error' => config('error.404_show')
            ], 404);
        }

        $clinicianNames = [];

        if ($location->clinicians) {
            $clinicianNames = $location->clinicians->map(function($clinician) {
                return '<span class="badge badge-info mr-1">'.$clinician->name.'</span>';
            })->toArray();
        }

        return response()->json([
            'location' => [
                'name' => $location->name,
                'email' => $location->email,
                'status' => $location->status == Location::YES ? '<span class="badge badge-success">Yes</span>': '<span class="badge badge-danger">No</span>' ,
                'clinicians' => $clinicianNames
            ]
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $clinicians = null;
        $location = Location::find($id);

        if (! $location) {
            return response()->json([
                'error' => config('error.404_show')
            ], 404);
        }

        if ($location->clinicians) {
            $clinicians = $location->clinicians;
        }

        return response()->json([
            'location' => $location,
            'clinicians' => $clinicians
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $location = Location::find($id);

        if (! $location) {
            return response()->json([
                'error' => config('error.404_show')
            ], 404);
        }

        try {

            if ($location->clinicians) {
                $location->clinicians()->detach();
            }

            $location->delete();
        }
        catch(\Exception $ex) {
            return response()->json([
                'error' => 'Something went wrong, the error is '. $ex->getMessage()
            ], 401);
        }

        return response()->json([
            'message' => 'Location removed successfully'
        ], 201);
    }

    public function changeLocationStatus(Request $request, $locationId)
    {
        $location = Location::find($locationId);

        if (! $location) {
            return response()->json([
                'error' => config('error.404_show')
            ], 404);
        }

        try {

            $location->update([
                'status' => $request->status
            ]);
        }
        catch(\Exception $ex) {
            return response()->json([
                'error' => 'Something went wrong, the error is '. $ex->getMessage()
            ], 401);
        }

        return response()->json([
            'message' => 'Location report sent successfully'
        ], 201);
    }
}
