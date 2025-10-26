<?php

namespace App\Modules\Page\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Page\Models\Page;
use App\Modules\Page\Models\DraftPage;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageSection extends Model
{
    use SoftDeletes;
    protected $fillable = ['title', 'type', 'content', 'page_id', 'order', 'slug', 'parent'];
    protected $table;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->table = (isset($_GET['preview']))?'draft_page_section':'page_section';
    }
    public function page()
    {
        if(isset($_GET['preview']))
        return $this->belongsTo(DraftPage::class,'page_id','id');
        else
        return $this->belongsTo(Page::class,'page_id','id');
    }

    public function children(){
        return $this->hasMany(PageSection::class,'parent','id');
    }

    public function parent(){
        return $this->belongsTo(PageSection::class,'parent','id');
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
