<?php

namespace App\Modules\News\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Modules\News\Models\News;
use App\Modules\News\Models\DraftNews;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsSection extends Model
{
    use SoftDeletes;
    protected $fillable = ['title', 'type', 'content', 'news_id', 'order', 'slug', 'parent'];
    protected $table;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->table = (isset($_GET['preview']))?'draft_news_section':'news_section';
    }
    public function news()
    {
        if(isset($_GET['preview']))
        return $this->belongsTo(DraftNews::class,'news_id','id');
        else
        return $this->belongsTo(News::class,'news_id','id');
    }

    public function children(){
        return $this->hasMany(NewsSection::class,'parent','id');
    }

    public function parent(){
        return $this->belongsTo(NewsSection::class,'parent','id');
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
}
