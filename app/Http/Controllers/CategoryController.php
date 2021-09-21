<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\User;
use Auth;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $categories = getAllCategories();
        $newCats = [];
        foreach ($categories as $cat) {
            array_push($newCats, [
                'id' => $cat->id,
                'parentID' => $cat->p_cat,
                'name' => $cat->cat_name,
                'count' => count($cat->equipments)
            ]);
        }
        
        $tree = buildTree($newCats, 'parentID', 'id');
        
        $nl_category_class = 'active';

        return view('pages.category', compact('categories', 'tree', 'nl_category_class'));
    }

    public function storeCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cat_name' => 'required'
        ]);
        
        if ($validator->fails()){
            return redirect(url()->previous())->withErrors($validator)->withInput();
        }
        
        $category = storeCategory($request);
        return redirect()->route('category.list');
    }

    public function deleteCategory($catId)
    {
        if (empty($catId)) {
            return redirect(url()->previous())
                                ->withErrors('Error: Category ID is empty!')
                                ->withInput();
        }

        $category = getCategory($catId);
        
        if (count($category->equipments) > 0) {
            return redirect(url()->previous())
                                ->withErrors('Error: \'' . $category->cat_name .'\' category has equipments!')
                                ->withInput();
        } else if (count($category->subCategories) > 0) {
            return redirect(url()->previous())
                                ->withErrors('Error: \'' . $category->cat_name .'\' category has sub categories!')
                                ->withInput();
        } else {
            deleteCategory($catId);
        }
        return redirect()->route('category.list');
    }

    public function getAllCategories(Request $request)
    {
        $categories = getAllCategories();

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }
}
