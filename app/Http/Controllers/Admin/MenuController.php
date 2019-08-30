<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MenuRequest;
use App\Http\Resources\Admin\MenuResource;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $menu = $request->get('all') ? Menu::get() : Menu::paginate();

        return MenuResource::collection($menu);
    }

    public function store(MenuRequest $request)
    {
        $inputs = $request->validated();
        $menu = Menu::create($inputs);

        return $this->created(MenuResource::make($menu));
    }


    public function show(Menu $menu)
    {
        return $this->success(MenuResource::make($menu));
    }

    public function update(MenuRequest $request, Menu $menu)
    {
        $inputs = $request->validated();
        $menu->update($inputs);

        return $this->created(MenuResource::make($menu));
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return $this->noContent();
    }
}
