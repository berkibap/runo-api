<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogItems extends Model
{
    use HasFactory;
    protected $table = 'catalog_items';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
