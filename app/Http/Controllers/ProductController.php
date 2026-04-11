<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\GameType;
use App\Models\Product;
use App\Models\Sentence;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function addProduct(Request $request)
    {
        
        $fields=$request->validate([
        'category_id' => 'required|exists:categories,id',
        'sub_category_id' => 'nullable|exists:subcategories,id',
        'name' => 'required|string',
        'price' => 'required|numeric|min:0',
        'sale' => 'nullable|numeric|min:0',
        'cost' => 'nullable|numeric|min:0',
        'featured' => 'required|in:yes,no',
        'isNew' => 'required|in:yes,no',
        'description' => 'nullable|string',
        'features' => 'nullable|string',
        'box_contents' => 'nullable|string',
        'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:1024',
        'image1' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        'image2' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        'image3' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        'image4' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        'image5' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        'image6' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        'gameTypes' => 'nullable|array',
        'gameTypes.*' => 'string'
        ]);
        $mainImage=$request->file('image');
        $customName='Product-'.Str::uuid().'.webp';
        $mainImage->storeAs('products', $customName);
        $fields['image']=$customName;

        foreach (['image1', 'image2', 'image3', 'image4','image5','image6'] as $img) {
            if($request[$img]) {
                $customName='Product-'.Str::uuid().'.webp';
                $request->file($img)->storeAs('products', $customName);
                $fields[$img]=$customName;
            }
        }
         if($request->features) {
            $fields['features']=json_encode(explode("\n", trim($fields['features'])));
        } else {
            $fields['features']=null;
        }
        
        if($request->box_contents) {
            $fields['box_contents']=json_encode(explode("\n", trim($fields['box_contents'])));
        } else {
            $fields['box_contents']=null;
        }
        
        if($fields['featured']=='yes') {
            $fields['featured']=true;
        } else {
            $fields['featured']=false;
        }
        if($fields['isNew']=='yes') {
            $fields['isNew']=true;
        } else {
            $fields['isNew']=false;
        }
        
        $product=Product::create($fields);
        if ($request->has('gameTypes')) {
            $gameTypeIds = GameType::whereIn('name', $request->gameTypes)->pluck('id');
            $product->gameTypes()->sync($gameTypeIds);
        }
        return redirect('/admin/products')->with('message', 'Product Added Successfully');
    }
    public function editProduct(Request $request, Product $product)
    {
        $fields=$request->validate([
        'category_id' => 'required|exists:categories,id',
        'sub_category_id' => 'nullable|exists:subcategories,id',
        'name' => 'required|string',
        'price' => 'required|numeric|min:0',
        'sale' => 'nullable|numeric|min:0',
        'cost' => 'nullable|numeric|min:0',
        'featured' => 'required|in:yes,no',
        'isNew' => 'required|in:yes,no',
        'is_available' => 'required|in:yes,no',
        'description' => 'nullable|string',
        'features' => 'nullable|string',
        'box_contents' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        'image1' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        'image2' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        'image3' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        'image4' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        'image5' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        'image6' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        'gameTypes' => 'nullable|array',
        'gameTypes.*' => 'string'
        ]);
        foreach (['image','image1', 'image2', 'image3', 'image4','image5','image6'] as $img) {
            if($request[$img]) {
                if ($product->$img && Storage::exists('products/' . $product->$img)) {
                    Storage::delete('products/' . $product->$img);
                }
                $customName='Product-'.Str::uuid().'.webp';
                $request->file($img)->storeAs('products', $customName);
                $fields[$img]=$customName;
            }
        }
       if($request->features) {
            $fields['features']=json_encode(explode("\n", trim($fields['features'])));
        } else {
            $fields['features']=null;
        }
        
        if($request->box_contents) {
            $fields['box_contents']=json_encode(explode("\n", trim($fields['box_contents'])));
        } else {
            $fields['box_contents']=null;
        }
        
        if($fields['featured']=='yes') {
            $fields['featured']=true;
        } else {
            $fields['featured']=false;
        }
        if($fields['isNew']=='yes') {
            $fields['isNew']=true;
        } else {
            $fields['isNew']=false;
        }
        
        if($fields['is_available']=='yes') {
            $fields['is_available']=true;
        } else {
            $fields['is_available']=false;
        }
        $product->update($fields);
        if ($request->has('gameTypes')) {
            $gameTypeIds = GameType::whereIn('name', $request->gameTypes)->pluck('id');
            $product->gameTypes()->sync($gameTypeIds);
        } else {
            // Remove all associated game types if none are provided
            $product->gameTypes()->sync([]);
        }
        return redirect('/admin/products')->with('message', 'Product Edited Successfully');

    }
    public function deleteProduct(Product $product)
    {
        foreach ([$product->image,$product->image1,$product->image2,$product->image3,$product->image4] as $img) {
                if ($product->$img && Storage::exists('products/' . $product->image)) {
                    Storage::delete('products/' . $product->image);
                }
        }
        $product->delete();
        return response()->json(['status'=>"removed"]);
    }
    public function productsSearch(Request $request)
    {
                $movingSentence=Sentence::first();

        $fields=$request->validate([
           'search'=>['required','max:255']
        ]);
        $products=Product::search($fields['search']) ->orderBy('created_at', 'desc') ->paginate(12);
        $categories=Category::all();
        $cart = session('cart_items', []);
        $cartQuantity = count($cart);
        return view('productsSearch', ['movingSentence'=>$movingSentence->sentence,'products'=>$products,'categories'=>$categories,'search'=>$fields['search'],'cartQuantity'=>$cartQuantity,'filter'=>false]);
    }
public function search(Request $request)
{
    $query = $request->get('q');

    $products = Product::where('name', 'like', '%' . $query . '%')
    ->orderBy('created_at','desc')
    ->where('is_available',true)
    ->limit(10)
    ->get(['id', 'name', 'image', 'price', 'sale']); // Include image


    return response()->json($products);
}

    public function adminProductsSearch(Request $request)
    {
        $fields=$request->validate([
           'search'=>['required','max:255']
        ]);
        $products=Product::search($fields['search']) ->orderBy('created_at', 'desc') ->paginate(12);
        $categories=Category::all();
        $gameTypes=GameType::all();
        return view('productsManage', ['products'=>$products,'categories'=>$categories,'gameTypes'=>$gameTypes]);
    }
    public function getSubCategories(Category $category)
    {
        $subCategories=$category->subcategories;
        return response()->json($subCategories);
    }
    public function adminFilter(Request $request)
    {
        
        $query = Product::with(['category', 'subCategory', 'gameTypes']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('subcategory_id') && $request->subcategory_id != 0) {
            $query->where('sub_category_id', $request->subcategory_id);
        }

        if ($request->filled('gameTypes') && is_array($request->gameTypes)) {
            $query->whereHas('gameTypes', function ($q) use ($request) {
                $q->whereIn('name', $request->gameTypes);
            });
        }
        $products = $query ->orderBy('created_at', 'desc') ->paginate(12);
        $categories=Category::all();
        $gameTypes=GameType::all();
        return view('productsManage', ['products'=>$products,'categories'=>$categories,'gameTypes'=>$gameTypes]);
    }
    public function filter(Request $request){
   $selectedGameTypeIds = $request->input('gameTypes', []);
    $subCategoryId = $request->input('subCategoryId');
    $subCategory=SubCategory::where('id',$subCategoryId)->first();
    
    if (empty($selectedGameTypeIds) || !$subCategoryId) {
        return redirect()->back()->with('message', 'Missing filters.');
    }

    $selectedGameTypeNames = GameType::whereIn('id', $selectedGameTypeIds)->pluck('name')->toArray();
    $searchStatement = implode(', ', $selectedGameTypeNames);

    $products = Product::where('sub_category_id', $subCategoryId)
        ->whereHas('gameTypes', function ($query) use ($selectedGameTypeIds) {
            $query->whereIn('game_types.id', $selectedGameTypeIds);
        })
        ->with('gameTypes', 'category')
        ->whereIn('id', function ($query) use ($selectedGameTypeIds) {
            $query->select('product_id')
                ->from('game_type_product')
                ->whereIn('game_type_id', $selectedGameTypeIds)
                ->groupBy('product_id')
                ->havingRaw('COUNT(DISTINCT game_type_id) = ?', [count($selectedGameTypeIds)]);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(12);

    $categories = Category::all();
    $cart = session('cart_items', []);
    $cartQuantity = count($cart);
$gameTypes=GameType::all();
        $movingSentence=Sentence::first();

    return view('productsSearch', [
        'movingSentence'=>$movingSentence->sentence,
        'products' => $products,
        'categories' => $categories,
        'search' => $searchStatement,
        'cartQuantity' => $cartQuantity,
        'subCategory'=>$subCategory,
        'filter'=>true,
        'gameTypes'=>$gameTypes
    ]);
}
}