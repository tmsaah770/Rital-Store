<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * عرض صفحة إدارة الأقسام والتصنيفات
     */
    public function index()
    {
        $categories = Category::withCount('products')->latest()->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * إضافة قسم جديد
     */
    public function store(Request $request)
    {
        $request->merge([
            'name' => strip_tags(trim((string)$request->input('name'))),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:150', 'unique:categories,name'],
        ], [
            'name.required' => 'من فضلك أدخل اسم القسم.',
            'name.min'      => 'اسم القسم يجب ألا يقل عن حرفين.',
            'name.max'      => 'اسم القسم طويل جداً.',
            'name.unique'   => 'اسم هذا القسم مسجل مسبقاً، يرجى اختيار اسم غير مكرر.',
        ]);

        $category = Category::create([
            'name' => $validated['name'],
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => 'تمت إضافة القسم بنجاح!',
                'category' => [
                    'id'             => $category->id,
                    'name'           => $category->name,
                    'products_count' => 0,
                    'created_at'     => $category->created_at ? $category->created_at->format('Y/m/d') : '-',
                ],
            ]);
        }

        return back()->with('success', 'تمت إضافة القسم بنجاح!');
    }

    /**
     * تعديل اسم القسم
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->merge([
            'name' => strip_tags(trim((string)$request->input('name'))),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:150', 'unique:categories,name,' . $category->id],
        ], [
            'name.required' => 'من فضلك أدخل اسم القسم.',
            'name.min'      => 'اسم القسم يجب ألا يقل عن حرفين.',
            'name.max'      => 'اسم القسم طويل جداً.',
            'name.unique'   => 'اسم هذا القسم مسجل مسبقاً، يرجى اختيار اسم غير مكرر.',
        ]);

        $category->update([
            'name' => $validated['name'],
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => 'تم تعديل اسم القسم بنجاح!',
                'category' => [
                    'id'   => $category->id,
                    'name' => $category->name,
                ],
            ]);
        }

        return back()->with('success', 'تم تعديل اسم القسم بنجاح!');
    }

    /**
     * حذف قسم وتلقائياً حذف المنتجات التابعة له (Cascade)
     */
    public function destroy($id)
    {
        $category = Category::withCount('products')->findOrFail($id);
        $deletedProductsCount = $category->products_count;
        $categoryName = $category->name;

        $category->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success'               => true,
                'message'               => 'تم حذف القسم "' . $categoryName . '" وحذف ' . $deletedProductsCount . ' منتج تابع له بنجاح!',
                'deleted_products_count'=> $deletedProductsCount,
            ]);
        }

        return back()->with('success', 'تم حذف القسم بنجاح!');
    }
}
