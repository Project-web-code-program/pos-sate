<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Branch extends Model
{
    use HasFactory;

    public $table = 'branches';

    protected $fillable = [
        'branch_code', 'branch_name', 'phone_number', 'address', 'social_media', 'created_by', 'updated_by',
    ];

    public $timestamps = true;

//     public static function boot()
//     {
//         parent::boot();

// // create a event to happen on updating
//         static::updating(function ($table) {
//             $table->updated_by = Auth::user()->id;
//         });

// // create a event to happen on saving
//         static::saving(function ($table) {
//             $table->created_by = Auth::user()->id;
//         });
//     }
}
