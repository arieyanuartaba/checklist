<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Item;

class Tchecklist extends Model 
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'due_interval', 'due_unit'
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
    

}
