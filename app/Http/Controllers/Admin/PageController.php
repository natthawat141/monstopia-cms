<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PageController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard');
    }

    public function index(string $module): View
    {
        abort_unless(in_array($module, ['projects', 'articles', 'services', 'team'], true), 404);
        return view('admin.content.index', ['module' => $module]);
    }

    public function create(string $module): View
    {
        abort_unless(in_array($module, ['projects', 'articles', 'services', 'team'], true), 404);
        return view('admin.content.form', ['module' => $module, 'mode' => 'create', 'resourceId' => null]);
    }

    public function edit(string $module, int $id): View
    {
        abort_unless(in_array($module, ['projects', 'articles', 'services', 'team'], true), 404);
        return view('admin.content.form', ['module' => $module, 'mode' => 'edit', 'resourceId' => $id]);
    }

    public function show(string $module, int $id): View
    {
        abort_unless(in_array($module, ['projects', 'articles', 'services', 'team'], true), 404);
        return view('admin.content.show', ['module' => $module, 'resourceId' => $id]);
    }
}
