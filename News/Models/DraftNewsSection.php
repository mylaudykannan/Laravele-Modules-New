<?php

namespace App\Modules\News\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Modules\News\Models\DraftNews;
use Illuminate\Database\Eloquent\SoftDeletes;

class DraftNewsSection extends Model
{
    use SoftDeletes;
    protected $fillable = ['title', 'type', 'content', 'news_id', 'order', 'slug'];
    protected $table = 'draft_news_section';

    public function news()
    {
        return $this->belongsTo(DraftNews::class,'news_id','id');
    }

    /* public function setNameAttribute($value)
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
    } */

    /* public function draft()
    {
        return $this->has(DraftNewsSection::class, 'pointslug', 'pointslug');
    } */
}
