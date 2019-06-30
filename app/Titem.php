<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Template;

class Titem extends Model 
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'urgency','due_interval', 'due_unit'
    ];

    
    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    
}
