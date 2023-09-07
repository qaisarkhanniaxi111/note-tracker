<?php

namespace App\Http\Controllers\Admin;

use App\Models\ErrorType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ErrorType\CreateRequest;
use Illuminate\Support\Str;
use DataTables;

class ErrorTypeController extends Controller
{
    public function index(Request $request)
    {
        $errorTypes = ErrorType::all();

        if ($request->ajax()) {

            return Datatables::of($errorTypes)
                ->addIndexColumn()
                ->addColumn('name', function($row){
                    return Str::limit($row->name, 20);
                })
                ->addColumn('status', function($row){
                    $status = null;

                    if ($row->status == ErrorType::DISABLED) {
                        $status = '<span class="badge badge-danger"><i class="fas fa-ban"></i></span>';
                    }
                    else if ($row->status == ErrorType::ACTIVE) {
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
        return view('admin.error_types.index');
    }

    /**
     * Store the specified resource.
     */

    public function store(CreateRequest $request)
    {

        if ($request->error_type_id) {

            $error = ErrorType::find($request->error_type_id);

            if (! $error) {
                return response()->json([
                    'error' => config('error.404_show')
                ], 404);
            }

            try {
                $error->update([
                    'name' => $request->name,
                    'status' => $request->status,
                ]);
            }
            catch(\Exception $ex) {
                return response()->json([
                    'error' => 'Something went wrong, the error is '. $ex->getMessage()
                ], 401);
            }


        }
        else {

            try {

                ErrorType::create([
                    'name' => $request->name,
                    'status' => $request->status
                ]);

            }
            catch(\Exception $ex) {
                return response()->json([
                    'error' => 'Something went wrong, the error is '. $ex->getMessage()
                ], 401);
            }
        }

        return response()->json([
            'message' => 'Error saved successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $error = ErrorType::find($id);

        if (! $error) {
            return response()->json([
                'error' => config('error.404_show')
            ], 404);
        }

        return response()->json([
            'error' => [
                'name' => $error->name,
                'status' => $error->status == ErrorType::ACTIVE ? '<span class="badge badge-success">Yes</span>': '<span class="badge badge-danger">No</span>' ,
            ]
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $error = ErrorType::find($id);

        if (! $error) {
            return response()->json([
                'error' => config('error.404_show')
            ], 404);
        }

        return response()->json([
            'error' => $error
        ], 201);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $error = ErrorType::find($id);

        if (! $error) {
            return response()->json([
                'error' => config('error.404_show')
            ], 404);
        }

        try {
            $error->delete();
        }
        catch(\Exception $ex) {
            return response()->json([
                'error' => 'Something went wrong, the error is '. $ex->getMessage()
            ], 401);
        }

        return response()->json([
            'message' => 'Error removed successfully'
        ], 201);
    }
}
