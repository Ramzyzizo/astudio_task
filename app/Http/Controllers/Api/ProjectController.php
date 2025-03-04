<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProjectsResource;
use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\AttributeValue;
use Dom\Attr;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB ;

class ProjectController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projects = Project::with('attributes')->get();
        $projects = ProjectsResource::collection($projects);
        return response()->json($projects);
  
    }
    public function my_projects(Request $request)
    {
        $user = $request->user();
        $projects = ProjectsResource::collection($user->projects);
        return response()->json($projects);
  
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request)
    {
        $user = $request->user();
        $validatedData = $request->validated();
        DB::beginTransaction();
        try {
        $project = $user->projects()->create($validatedData);
        $attributes = Arr::pull($validatedData, 'attributes', []);
        if (!empty($attributes)) {
            $pivotData = [];
            foreach ($attributes as $attribute) {
                $pivotData[$attribute['attribute_id']][] = [
                    'value' => $attribute['value'],
                    'start_date' => $attribute['start_date'],
                    'end_date' => $attribute['end_date'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            foreach ($pivotData as $attribute_id => $values) {
                foreach ($values as $value) {
                    $project->attributes()->attach($attribute_id, $value);
                }
            }
        }
        DB::commit();
        return response()->json([
            'message' => 'Project has been created successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong while creating the project.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);
        $project =  new ProjectsResource($project);
        return response()->json($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $this->authorize('view', $project);
        $validatedData = $request->validated();
        DB::beginTransaction();
        try{
        $project->update($validatedData);
        $attributes = Arr::pull($validatedData, 'attributes', []);
        if (!empty($attributes)) {
            $project->attributes()->detach();
            $pivotData = [];
            foreach ($attributes as $attribute) {
                $pivotData[$attribute['attribute_id']][] = [
                    'value' => $attribute['value'],
                    'start_date' => $attribute['start_date'],
                    'end_date' => $attribute['end_date'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            foreach ($pivotData as $attribute_id => $values) {
                foreach ($values as $value) {
                    $project->attributes()->attach($attribute_id, $value);
                }
            }
        }
        else{
            $project->attributes()->detach();
        }
        
        
        $project->update($request->validated());
        DB::commit();

        return response()->json([
            'message' => 'Project has been updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong while updating the project.',
                'error' => $e->getMessage()
            ], 500);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('view', $project);
        $project->delete();
        return response()->json([
            'message' => 'Project has been deleted successfully!']);
    }


    public function assign_me(Project $project, Request $request)
    {
        $user = $request->user();
        $project->users()->syncWithoutDetaching($user->id);
        return response()->json([
            'message' => 'You have been assigned to the project!']);
    }
}
