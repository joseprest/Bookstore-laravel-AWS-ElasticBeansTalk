<?php namespace Manivelle\Http\Controllers\Admin;

use Manivelle\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
}
