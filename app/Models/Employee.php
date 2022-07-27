<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public $table = 'employees';

    protected $fillable = [
        'full_name', 'nick_name', 'phone_number', 'branch_id', 'created_by', 'updated_by',
    ];

    public $timestamps = true;
}
