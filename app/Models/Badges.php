<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badges extends Model
{
    use HasFactory;
    protected $table = 'rcms_badges';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
