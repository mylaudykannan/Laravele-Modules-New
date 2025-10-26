<?php

namespace App\Modules\News\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Newscategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['content', 'slug', 'title', 'image', 'order', 'status'];
    protected $table = 'newscategory';

    /* protected static function booted()
    {
        static::addGlobalScope('enable', function (Builder $builder) {
            $builder->where('status', 'Enable');
        });
    } */

    public function scopeActive($query)
    {
        return $query->where('status', 'Enable');
    }

    public function setNameAttribute($value)
    {
        // $this->attributes['name'] = $value;
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
}
