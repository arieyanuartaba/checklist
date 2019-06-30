<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Titem;
use App\Tchecklist;

class Template extends Model 
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public function items(){

        return $this->hasMany(Titem::class);
    }

    public function checklist()
    {
        return $this->hasOne(Tchecklist::class);
    }

}
