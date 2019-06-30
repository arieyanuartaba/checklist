<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Checklist;

class Item extends Model 
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'user_id', 'is_completed', 'due', 'urgency',
        'checklist_id', 'assignee_id', 'task_id', 'completed_at',
        'last_update_by'
    ];

    public function checklist(){

        return $this->belongsTo(Checklist::class);
    }

}
