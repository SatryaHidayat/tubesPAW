<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class OrderController extends Controller
{
    public function index()
    {
        $menus = Menu::all();

        return view('user.menu_list', compact('menus'));
    }
}
