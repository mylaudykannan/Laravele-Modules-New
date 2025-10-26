<?php

namespace App\Modules\Menu\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public $timestamps = true;
    protected $table = 'menus';
    protected $fillable = ['title', 'content'];

    function getMenu($menu_slug)
    {
        return $this->where(['slug' => $menu_slug])->first();
    }

    function getActiveMenu($menu_slug)
    {
        $menu = $this->where(['slug' => $menu_slug])->first();
        $active_items = json_decode($menu['active_items']);
        return $active_items;
    }

    function getUrlById($id)
    {
        $slug = $this->getSlugById($id);
        return $this->parseUri($slug);
    }

    function parseUri($url)
    {
        if (!$this->validate_url($url)) {
            return url($url);
        } else {
            return $url;
        }
    }

    function validate_url($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        $encoded_path = array_map('urlencode', explode('/', $path));
        $url = str_replace($path, implode('/', $encoded_path), $url);

        return filter_var($url, FILTER_VALIDATE_URL) ? true : false;
    }

    function getActiveMenuClass($menu, $active_url, $parent = 'parent')
    {
        if (empty($active_url)) {
            return false;
        }
        if ($menu->href == $active_url) {
            return 'active';
        } else if (count($menu->children) >= 1) {
            if (collect($menu->children)->where('href', '=', $active_url)->count() >= 1) {
                return 'active  ';
            } else {
                return 'inactive' . $active_url;
            }
        }
    }
}
