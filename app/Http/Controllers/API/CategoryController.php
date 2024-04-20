<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class CategoryController extends Controller
{
    //
    public function getCategoryList(): JsonResponse
    {
        $categories = Category::query()->orderBy('name', 'asc')->get();
        $categories = $categories->map(function ($category) {
           return [
               'id'=>$category->id,
               'name'=>$category->name,
               'image'=>$category->getFirstMediaUrl('category_image'),
           ];
        });
        return response()->json($categories, 200);
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addCategory(Request $request): JsonResponse
    {
        $data = $request->all();
        /** @var Category $category */
        $category = Category::query()->create($data);
        if ($request->hasFile('image')) {
            $category->addMedia($request->file('image'))->toMediaCollection('category_image');
        }
        return response()->json('201');
    }
}
