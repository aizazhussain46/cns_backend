<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Status;
use App\Patient;
use Illuminate\Support\Facades\Auth;
use Validator;
class DoctorController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:api')->except('register','login','logout');
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::where('users.role_id', 3)->leftJoin('roles', 'users.role_id', '=', 'roles.id')
        ->leftJoin('districts', 'users.district_id', '=', 'districts.id')
        ->leftJoin('statuses', 'users.status_id', '=', 'statuses.id')
        ->select('users.*','roles.role', 'districts.district', 'statuses.status')
        ->get();
        return response()->json([
			'success' => true,
			'data' => $user
		],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
			'name' => 'required', 
			'email' => 'required|email|unique:users', 
			'mobile_no' => 'required|unique:users',
			'district_id' => 'required',
            'status_id' => 'required',
            'pmdc_no' => 'required',
			'clinic' => 'required'
		]); 
		if ($validator->fails()) { 

			return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		
		]); 

		}

		$input = $request->all();
		$input['role_id'] = 3;
		$input['username'] = strtolower($input['name']);
		$add = User::create($input); 

		$user = User::where('users.id', $add->id)->leftJoin('roles', 'users.role_id', '=', 'roles.id')
        ->leftJoin('districts', 'users.district_id', '=', 'districts.id')
        ->leftJoin('statuses', 'users.status_id', '=', 'statuses.id')
        ->select('users.*','roles.role', 'districts.district', 'statuses.status')
        ->first();
        return response()->json([
			'success' => true,
			'data' => $user
		],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [ 
			'name' => 'required', 
			'email' => 'required|email', 
			'mobile_no' => 'required',
			'district_id' => 'required',
            'status_id' => 'required',
            'pmdc_no' => 'required',
			'clinic' => 'required'
		]); 
		if ($validator->fails()) { 

			return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		
		]); 

		}

		$input = $request->all();
		$input['username'] = strtolower($input['name']);
		$update = User::where('id', $id)->update($input); 
		
		$user = User::where('users.id', $id)->leftJoin('roles', 'users.role_id', '=', 'roles.id')
        ->leftJoin('districts', 'users.district_id', '=', 'districts.id')
        ->leftJoin('statuses', 'users.status_id', '=', 'statuses.id')
        ->select('users.*','roles.role', 'districts.district', 'statuses.status')
        ->first();
        return response()->json([
			'success' => true,
			'data' => $user
		],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return (User::find($id)->delete()) 
                ? [ 'response_status' => true, 'message' => 'user has been deleted' ] 
                : [ 'response_status' => false, 'message' => 'user cannot delete' ];
    }
}
