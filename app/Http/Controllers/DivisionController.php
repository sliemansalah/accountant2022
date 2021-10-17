<?php

namespace App\Http\Controllers;

use App\Models\division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DivisionController extends Controller
{

    public function index()
    {
        $division= Division::all();
        return $division;
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());   
        }

        $division = Division::create($input);
        return response()->json([
            "success" => true,
            "message" => "division created successfully.",
            "data" => $division
        ]);

    }

    public function show($id)
    {
        $division = Division::find($id);
        if (is_null($division)) {
        return $this->sendError('Division not found.');
        }
        return $division;
    }

    public function update(Request $request, division $division)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
        'name' => 'required'
        ]);

        if($validator->fails()){
        return $this->sendError('Validation Error.', $validator->errors());       
        }

        $division->name = $input['name'];
        $division->save();
        
        return response()->json([
            "success" => true,
            "message" => "Division updated successfully.",
            "data" => $division
        ]);
    }
    public function destroy(division $division)
    {
        $division->delete();
            return response()->json([
            "success" => true,
            "message" => "Division deleted successfully.",
            "data" => $division
        ]);
    }
}
