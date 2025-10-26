<?php

namespace App\Modules\Menu\Controller;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Menu\Models\Menu;

class MenuController extends Controller
{
    public function create()
    {
        $title = $_GET['menu'];
        $menu = Menu::where('title', $title)->first();
        return view('Menu::admin.create', ['menu' => $menu, 'title' => $title]);
    }

    public function store(Request $request)
    {
        $match = ['title' => $request->title];
        $data = ['title' => $request->title, 'content' => $request->menu];
        Menu::updateOrCreate($match, $data);
        return redirect()->back();
    }
}
