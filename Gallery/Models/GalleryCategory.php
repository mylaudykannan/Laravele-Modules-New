<?php

namespace App\Modules\Gallery\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryCategory extends Model 
{
    protected $fillable = ['category'];
    protected $table = 'gallery_category';
}
