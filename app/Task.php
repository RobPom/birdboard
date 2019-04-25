<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Task extends Model
{
    protected $guarded = [];

    protected $touches = ['project'];

    protected $casts = [
        'completed' => 'boolean'
    ];


    public function complete()
    {
        if(! $this->completed) {    
            $this->update(['completed' => true]);
            $this->recordActivity('completed_task');
        }
    }
    
    public function incomplete()
    {
        if($this->completed) {
            $this->update(['completed' => false]);
            $this->recordActivity('marked_incomplete');
        }
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    public function path()
    {
        return "/projects/{$this->project->id}/tasks/{$this->id}";
    }

    public function recordActivity($description)
    {
        $this->activity()->create([
            'project_id' => $this->project->id,
            'description' => $description
        ]);
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }
}
