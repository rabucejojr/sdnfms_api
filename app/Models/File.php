<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\Contracts\HasApiTokens;

class File extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'path', 'size'];
}
