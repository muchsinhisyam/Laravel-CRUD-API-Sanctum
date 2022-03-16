<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Freelancer;
use App\Http\Resources\FreelancerResource;

class FreelancerController extends Controller
{
    public function index()
    {
        $data = Freelancer::latest()->get();
        return response()->json([FreelancerResource::collection($data), 'Freelancer data fetched.']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'skills' => 'required|string',
            'domicile' => 'required|string|max:255'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $data = Freelancer::create([
            'name' => $request->name,
            'position' => $request->position,
            'skills' => $request->skills,
            'domicile' => $request->domicile
         ]);
        
        return response()->json(['Freelancer data created successfully.', new FreelancerResource($data)]);
    }

    public function show($id)
    {
        $data = Freelancer::find($id);
        if (is_null($data)) {
            return response()->json('Data not found', 404); 
        }
        return response()->json([new FreelancerResource($data)]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'skills' => 'required|string',
            'domicile' => 'required|string|max:255'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $data = Freelancer::find($id);
        if (is_null($data)) {
            return response()->json('Data not found', 404); 
        }

        $data->name = $request->name;
        $data->position = $request->position;
        $data->skills = $request->skills;
        $data->domicile = $request->domicile;
        $data->update();
        
        return response()->json(['Freelancer data updated successfully.', new FreelancerResource($data)]);
    }

    public function destroy($id)
    {
        $data = Freelancer::find($id);
        if (is_null($data)) {
            return response()->json('Data not found', 404); 
        }
        $data->delete();
        return response()->json('Freelancer deleted successfully');
    }
}
