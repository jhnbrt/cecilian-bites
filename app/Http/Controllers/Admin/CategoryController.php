<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('Admin.categories.index', [
            'categories' => Category::query()->withCount('menuItems')->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $category = Category::create([
            'name' => $request->validated('name'),
            'slug' => Str::slug($request->validated('name')),
            'sort_order' => $request->validated('sort_order') ?? ((int) Category::query()->max('sort_order') + 1),
        ]);

        return back()->with('status', 'Category “'.$category->name.'” was created.');
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update([
            'name' => $request->validated('name'),
            'slug' => Str::slug($request->validated('name')),
            'sort_order' => $request->validated('sort_order') ?? $category->sort_order,
        ]);

        return back()->with('status', 'Category “'.$category->name.'” was updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->menuItems()->exists()) {
            return back()->withErrors([
                'category' => '“'.$category->name.'” still has dishes. Move or delete them first.',
            ]);
        }

        $category->delete();

        return back()->with('status', 'Category “'.$category->name.'” was deleted.');
    }
}
