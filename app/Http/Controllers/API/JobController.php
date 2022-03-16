<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Job;
use App\Http\Resources\JobResource;

class JobController extends Controller
{
    public function index()
    {
        $data = Job::latest()->get();
        return response()->json([JobResource::collection($data), 'Job data fetched.']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'freelancer_id' => 'required|integer',
            'job_name' => 'required|string|max:255',
            'job_description' => 'required|string',
            'price' => 'required|integer',
            'currency' => 'required|string|max:255'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $data = Job::create([
            'freelancer_id' => $request->freelancer_id,
            'job_name' => $request->job_name,
            'job_description' => $request->job_description,
            'price' => $request->price,
            'currency' => $request->currency
         ]);
        
        return response()->json(['Job data created successfully.', new JobResource($data)]);
    }

    public function show($id)
    {
        $data = Job::find($id);
        if (is_null($data)) {
            return response()->json('Data not found', 404); 
        }
        return response()->json([new JobResource($data)]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'freelancer_id' => 'required|integer',
            'job_name' => 'required|string|max:255',
            'job_description' => 'required|string',
            'price' => 'required|integer',
            'currency' => 'required|string|max:255'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $data = Job::find($id);
        if (is_null($data)) {
            return response()->json('Data not found', 404); 
        }

        $data->freelancer_id = $request->freelancer_id;
        $data->job_name = $request->job_name;
        $data->job_description = $request->job_description;
        $data->price = $request->price;
        $data->currency = $request->currency;
        $data->save();
        
        return response()->json(['Job data updated successfully.', new JobResource($data)]);
    }

    public function destroy($id)
    {
        $data = Job::find($id);
        if (is_null($data)) {
            return response()->json('Data not found', 404); 
        }
        $data->delete();
        return response()->json('Job deleted successfully');
    }
}
