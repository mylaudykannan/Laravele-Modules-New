<?php

namespace App\Modules\News\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Modules\News\Models\NewsSection;
use App\Modules\News\Models\DraftNewsSection;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;
    protected $fillable = ['content', 'slug', 'locale', 'title', 'image', 'pointslug', 'meta_description', 'meta_keywords', 'analytics', 'status', 'publish_on'];
    protected $table;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->table = (isset($_GET['preview']))?'draft_news':'news';
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

    public function sections()
    {
        if(isset($_GET['preview']))
        return $this->hasMany(DraftNewsSection::class, 'news_id', 'id')->orderBy('order', 'ASC');
        else
        return $this->hasMany(NewsSection::class, 'news_id', 'id')->orderBy('order', 'ASC');
    }

    public function draft()
    {
        return $this->hasOne(DraftNews::class, 'pointslug', 'pointslug');
    }

    public function getNews($slugs){
        if(is_array($slugs))
        return $this->with('sections')->whereIn('pointslug',$slugs)->get()->groupBy('slug');
        else
        return $this->with('sections')->where('pointslug',$slugs)->first();
    }
}
