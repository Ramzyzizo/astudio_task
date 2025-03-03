<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProjectsResource;
use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ProjectController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
        $user->projects()->create($request->validated());        
        return response()->json([
            'message' => 'Project has been created successfully!']);
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
        $project->update($request->validated());
        return response()->json([
            'message' => 'Project has been updated successfully!']);
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
}
