<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MenuRequest;
use App\Http\Resources\Admin\MenuResource;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function index()
    {
        $data = MenuResource::collection(Menu::with('roles')->paginate())->withIds(['roles']);

        return $this->success($data);
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

        if (isset($inputs['roles'])) {
            $menu->roles()->sync($inputs['roles']);
        }

        return $this->created(MenuResource::make($menu));
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return $this->noContent();
    }

    public function allMenu(Menu $menu)
    {
        $data = $menu->toTree();

        return $this->success($data);
    }
}
