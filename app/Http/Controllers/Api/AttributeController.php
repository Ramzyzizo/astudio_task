<?php

namespace App\Http\Controllers\Api;

use App\Models\Attribute;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttributeRequest;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aatributes =  Attribute::all()->select('id', 'name','type');   
        return response()->json($aatributes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeRequest $request)
    {
        Attribute::create($request->validated());
        return response()->json([ 'message' => 'Attribute has been created successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attribute $attribute)
    {
        return response()->json($attribute);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeRequest $request, Attribute $attribute)
    {
        $attribute->update($request->validated());
        return response()->json([ 'message' => 'Attribute has been updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return response()->json([ 'message' => 'Attribute has been deleted successfully!']);
    }
}
