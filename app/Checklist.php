<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Item;

class Checklist extends Model 
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'object_domain', 'object_id', 'description', 'is_completed', 'completed_at',
        'updated_by', 'due', 'urgency'
    ];

    public function items(){

        return $this->hasMany(Item::class);
    }

}
