<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use DB;
use Validator;

class CompanyController extends Controller
{
    /**
    *@author Odole Olukayode <kaythinks@gmail.com>
    *@var object $fleet 
    */

    protected $fleet;

    public function __construct(Company $company)
    {
        $this->fleet = $company;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fleets = DB::table('companies')
                 ->select('category', DB::raw('count(id) as num_of_fleet'))
                 ->groupBy('category')
                 ->get();

        if (count($fleets) < 1) {
            return response()->json(['message'=>'No Data']);
        }

        return response()->json($fleets,200);  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credentials = $request->all();

        $rules = [
            'category' => 'required|string|max:255',
            'car_make' => 'required|string|max:255',
            'plate_number' => 'required|string|max:255',
            'car_colour' => 'required|string|max:255',
        ];

        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages()]);
        }

        $this->fleet->create($credentials);
        return response()->json(['success'=> 'Fleet successffully created'],201); 
    }

    /**
     * Display the specified resource.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show = $this->fleet->find($id);
        if (!$show) {
           return response()->json(['success'=> false],404); 
        }
        return response()->json($show,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,Request $request)
    {
        $credentials = $request->all();

        $rules = [
            'category' => 'nullable|string|max:255',
            'car_make' => 'nullable|string|max:255',
            'plate_number' => 'nullable|string|max:255',
            'car_colour' => 'nullable|string|max:255',
        ];

        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages()]);
        }
        $input = $this->fleet->find($id);
        if (!$input) {
           return response()->json(['success'=> false],404); 
        }
        $input->update($credentials);
        return response()->json(['success'=> 'Successfully updated'],200); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $input = $this->fleet->find($id);
        if (!$input) {
           return response()->json(['success'=> false],404); 
        }
        $input->delete();
        return response()->json(['success'=>'Successfully deleted'],204);
    }
}
