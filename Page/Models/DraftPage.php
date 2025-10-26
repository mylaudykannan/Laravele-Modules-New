<?php

namespace App\Modules\Page\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Page\Models\PageSection;
use Illuminate\Database\Eloquent\SoftDeletes;

class DraftPage extends Model
{
    use SoftDeletes;
    protected $fillable = ['content', 'slug', 'locale', 'title', 'image', 'pointslug', 'meta_description', 'meta_keywords', 'analytics'];
    protected $table = 'draft_page';

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
        return $this->hasMany(PageSection::class, 'page_id', 'id')->orderBy('order', 'ASC');
    }
}
