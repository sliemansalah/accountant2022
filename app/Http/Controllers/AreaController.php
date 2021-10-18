<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    public function index()
    {
        $allAreas = Area::get();
        $rootAreas= $allAreas->whereNull('parent_id');
        self::formatTree($rootAreas, $allAreas); 
        return $rootAreas;
    }

    public static function formatTree($areas, $allAreas) {
        foreach($areas as $area) {
            $area->children = $allAreas->where('parent_id', $area->id)->values();
            if($area->children->isNotEmpty()) {
                self::formatTree($area->children, $allAreas);
            }
        }
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

        $area = Area::create($input);
        return response()->json([
            "success" => true,
            "message" => "area created successfully.",
            "data" => $area
        ]);

    }

    public function show($id)
    {
        $area = Area::find($id);
        if (is_null($area)) {
        return $this->sendError('Area not found.');
        }
        return $area;
    }
    public function showWithChildren(Area $area)
    {   
        try{
            $childs = $area->childs;
            foreach($childs as $child) {
                self::getChilds($child);
            }
            
            return $area;
        }catch(Exception $e){
            return $this->sendError('Area not found.', $e->getMessage());
        }
    }
    public static function getChilds(Area $area) {
        foreach($area->childs as $child) {
            $childs = $child->childs;
            if($childs->isNotEmpty()) {
                self::getChilds($child);
            }
        }
    }

    public function update(Request $request, Area $area)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
        'name' => 'required'
        ]);

        if($validator->fails()){
        return $this->sendError('Validation Error.', $validator->errors());       
        }

        $area->name = $input['name'];
        $area->parent_id = $input['parent_id'];
        $area->save();
        
        return response()->json([
            "success" => true,
            "message" => "Area updated successfully.",
            "data" => $area
        ]);
    }

    public function destroy(Area $area)
    {
        $area->delete();
            return response()->json([
            "success" => true,
            "message" => "Area deleted successfully.",
            "data" => $area
        ]);
    }
}
