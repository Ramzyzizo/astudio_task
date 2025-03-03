<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    
    public function view(User $user, Project $project): bool
    {
        return $project->users()->where('user_id', $user->id)->exists();
    }

    public function update(User $user, Project $project): bool
    {
        return $project->users()->where('user_id', $user->id)->exists();
    }

}
