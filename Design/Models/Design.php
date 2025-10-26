<?php

namespace App\Modules\Design\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Design\Models\DesignSection;
use App\Modules\Design\Models\DraftDesignSection;
use Illuminate\Database\Eloquent\SoftDeletes;

class Design extends Model
{
    use SoftDeletes;

    protected $fillable = ['content', 'slug', 'locale', 'title', 'image', 'pointslug', 'meta_description', 'meta_keywords', 'analytics', 'status', 'publish_on'];
    protected $table;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->table = (isset($_GET['preview']))?'draft_design':'design';
    }
    public function setNameAttribute($value)
    {
        $slug = Str::slug($value);
        sc:
        $slugcount = $this->slugcount($slug);
        if ($slugcount > 0) {
            $slug = $slug . '-1';
            goto sc;
        }
        if ($slugcount < 1)
            return $slug;
    }

    public function slugcount($slug)
    {
        return $slugcount = $this->where('slug', $slug)->where('id', '!=', $this->id)->count();
    }

    public function draft()
    {
        return $this->hasOne(DraftDesign::class, 'pointslug', 'pointslug');
    }
}
