<?php

namespace App\Modules\Page\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Page\Models\DraftPage;
use Illuminate\Database\Eloquent\SoftDeletes;

class DraftPageSection extends Model
{
    use SoftDeletes;
    protected $fillable = ['title', 'type', 'content', 'page_id', 'order', 'slug'];
    protected $table = 'draft_page_section';

    public function page()
    {
        return $this->belongsTo(DraftPage::class,'page_id','id');
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
        return $this->has(DraftPageSection::class, 'pointslug', 'pointslug');
    } */
}
