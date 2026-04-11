<?php
namespace App\Http\Controllers;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Coming;
use App\Models\ComingProduct;
use App\Models\GameType;
use App\Models\Order;
use App\Models\Product;
use App\Models\Sentence;
use App\Models\SubCategory;
use App\Models\Type;
use App\Models\Watch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PagesController extends Controller
{
    
    public function testing(){
        $products=Product::count();
        return $products;
    }
    public function homePage()
    {
        // In homePage():
$banners = Banner::with('product')->orderBy('created_at', 'desc')->get();

$newproducts = Product::with('category')
    ->where('isNew', true)
    ->where('is_available', true)
    ->orderBy('created_at', 'desc')
    ->take(9)->get();

$featuredProducts = Product::with('category')
    ->where('featured', true)
    ->where('is_available', true)
    ->orderBy('created_at', 'desc')
    ->take(9)->get();

// Categories with subcategories (used in navbar on EVERY page)
$categories = Category::with('subcategories')->get();
        $comingSoon=Coming::first();
        $movingSentence=Sentence::first();
        $cart = session('cart_items', []);
        $cartQuantity = count($cart);
        $controllers=Product::where('category_id',1)->where('sub_category_id',5)->orderBy('created_at','desc')->take(7)->get();
        $watches=Watch::where('featured',true)->where('is_available',true)->orderBy('created_at', 'desc') 
                                   ->take(9)->get();
        return view('home', ['watches'=>$watches,'categories' => $categories,'banners' => $banners,'newproducts'=>$newproducts,'featuredProducts' => $featuredProducts,'comingSoon'=>$comingSoon,'cartQuantity'=>$cartQuantity,'controllers'=>$controllers,'activeIndex' => 0,'movingSentence'=>$movingSentence->sentence]);
    }
    
    public function productDetailsPage(Product $product)
    {
        $hasGameTypes=false;
        $movingSentence=Sentence::first();
        $categories = Category::with('subcategories')->get();
        $relatedProductsQuery = Product::where('category_id', $product->category_id)
        ->where('sub_category_id', $product->sub_category_id)
        ->where('id', '!=', $product->id)
        ->orderBy('created_at', 'desc');

        $gameTypes = $product->gameTypes;  // single query
        if ($gameTypes->isNotEmpty()) {
            $gameTypeIds = $gameTypes->pluck('id')->toArray();
            $hasGameTypes=true;
        
            $relatedProductsQuery->whereHas('gameTypes', function ($query) use ($gameTypeIds) {
                $query->whereIn('game_types.id', $gameTypeIds);
            });
        }

        $relatedProducts = $relatedProductsQuery->take(5)->get();
        $features=json_decode($product->features, true);
        $boxContents=json_decode($product->box_contents, true);
        $cart = session('cart_items', []);
        $cartQuantity = count($cart);
        return view('productDetails', ['hasGameTypes'=>$hasGameTypes,'movingSentence'=>$movingSentence->sentence,'categories' => $categories,'relatedProducts'=>$relatedProducts,'product'=>$product,'features'=>$features,'boxContents'=>$boxContents,'cartQuantity'=>$cartQuantity,'pageType'=>'product']);
    }
    public function watchDetailsPage(Watch $watch){

        $movingSentence=Sentence::first();
        $categories = Category::with('subcategories')->get();
       $relatedProducts = Watch::orderBy('created_at','desc')->take(5)->get();
        $features=json_decode($watch->features, true);
        $boxContents=json_decode($watch->box_contents, true);
        $cart = session('cart_items', []);
        $cartQuantity = count($cart);
        return view('productDetails', ['hasGameTypes'=>false,'movingSentence'=>$movingSentence->sentence,'categories' => $categories,'relatedProducts'=>$relatedProducts,'product'=>$watch,'features'=>$features,'boxContents'=>$boxContents,'cartQuantity'=>$cartQuantity,'pageType'=>'watch']);
    
    }
    public function productsPage(Category $category)
    {
        $movingSentence=Sentence::first();
        $categories = Category::with('subcategories')->get();
        $cart = session('cart_items', []);
        $cartQuantity = count($cart);
        $products=$category->products()->orderBy('created_at','desc')->paginate(12);
        return view('productsPage', ['movingSentence'=>$movingSentence->sentence,'categories' => $categories,'category'=>$category,'products'=>$products,'cartQuantity'=>$cartQuantity,'pageType'=>'product']);
    }
public function productsBySubPage(SubCategory $subCategory)
{
    if ($subCategory->name === 'Games') {
        return redirect()->route('allGamesRoute', ['subCategory' => $subCategory->id]);
    }
            $movingSentence=Sentence::first();

    $categories = Category::with('subcategories')->get();
    $cart = session('cart_items', []);
    $cartQuantity = count($cart);
    $products=$subCategory->products()->orderBy('created_at','desc')->paginate(12);
    return view('productsBySub', [
        'movingSentence'=>$movingSentence->sentence,
        'categories' => $categories,
        'subCategory' => $subCategory,
        'isGameType' => false,
        'cartQuantity' => $cartQuantity,
        'gameTypes' => [],
        'products'=>$products
    ]);
}

    public function productsByGameType(SubCategory $subCategory, GameType $gameType)
    {
                $movingSentence=Sentence::first();

                 $categories = Category::with('subcategories')->get();
                $gameTypes=GameType::all();
        $cart = session('cart_items', []);
        $cartQuantity = count($cart); 

        $products = $gameType->products()
                             ->where('sub_category_id', $subCategory->id)
                             ->orderBy('created_at', 'desc') 
                             ->paginate(24);
        
        return view('productsBySub', ['movingSentence'=>$movingSentence->sentence,'categories' => $categories,'gameType'=>$gameType,'isGameType'=>true,'products'=>$products,'subCategory'=>$subCategory,'gameTypes'=>$gameTypes,'cartQuantity'=>$cartQuantity]);
    }
    public function productsByAllGames(SubCategory $subCategory)
    {
                $movingSentence=Sentence::first();

        $categories = Category::with('subcategories')->get();
        $gameTypes=GameType::all();
        $cart = session('cart_items', []);
        $cartQuantity = count($cart); 
        $products=$subCategory->products()->orderBy('created_at', 'desc')->simplePaginate(12);
        return view('productsBySub', ['movingSentence'=>$movingSentence->sentence,'categories' => $categories,'gameType'=>"All Games",'isGameType'=>true,'products'=>$products,'subCategory'=>$subCategory,'gameTypes'=>$gameTypes,'cartQuantity'=>$cartQuantity]);
    }
    public function loginPage()
    {
        return view('login');
    }
    public function adminPage()
    {
        return view('admin');
    }

    public function manageProductsPage()
    {
        $products=Product::orderBy('created_at', 'desc') ->paginate(12);
        $categories = Category::with('subcategories')->get();
        $gameTypes=GameType::all();
        return view('productsManage', ['products'=>$products,'categories'=>$categories,'gameTypes'=>$gameTypes]);
    
    }
     public function manageWatchesPage()
    {
        $watches=Watch::orderBy('created_at', 'desc') ->paginate(12);
        return view('watchesManage', ['watches'=>$watches]);
    
    }
    public function bannersPage()
    {
        $banners=Banner::all();
        return view('banners', ['banners'=>$banners]);
    }
    public function manageComingPage()
    {
        $image=Coming::first();
        $comingProducts=ComingProduct::paginate(12);
        return view('manageComing', ['image' => $image,'comingProducts'=>$comingProducts]);
    }
    public function changePasswordPage()
    {
        return view('changePassword');
    }
    public function addProductPage()
    {
        $categories = Category::with('subcategories')->get();
        $gameTypes=GameType::all();
        return view('addProduct', ['categories'=>$categories,'gameTypes'=>$gameTypes]);
    }
     public function addWatchPage()
    {
        $types=Type::all();
        return view('addWatch',['types'=>$types]);
    }
    public function addBannerPage()
    {
        $products=Product::where('is_available',true) ->orderBy('created_at', 'desc') ->get();
        return view('addBanner', ['products' => $products]);
    }
    public function editBannerPage(Banner $banner)
    {
        $products=Product::where('is_available',true) ->orderBy('created_at', 'desc') ->get();
        return view('editBanner', ['products' => $products,'banner' => $banner]);

    }
    public function editProductPage(Product $product)
    {
        $categories = Category::with('subcategories')->get();
        $gameTypes=GameType::all();
$decodedFeatures = json_decode($product->features ?? '[]', true);
$decodedBox = json_decode($product->box_contents ?? '[]', true);

$features = is_array($decodedFeatures) ? implode("\n", $decodedFeatures) : '';
$boxContents = is_array($decodedBox) ? implode("\n", $decodedBox) : '';

        $product_gameTypes=$product->gameTypes->pluck('id')->toArray();
        return view('editProduct', [
                                                'product'=>$product,
                                                'categories'=>$categories,
                                                'gameTypes'=>$gameTypes,
                                                'features'=>$features,
                                                'boxContents'=>$boxContents,
                                                'product_gameTypes'=>$product_gameTypes]);
    }
     public function editWatchPage(Watch $watch)
    {
        $types=Type::all();
$decodedFeatures = json_decode($watch->features ?? '[]', true);
$decodedBox = json_decode($watch->box_contents ?? '[]', true);

$features = is_array($decodedFeatures) ? implode("\n", $decodedFeatures) : '';
$boxContents = is_array($decodedBox) ? implode("\n", $decodedBox) : '';

        return view('editWatch', [
                                                'watch'=>$watch,
                                                'types'=>$types,
                                                'features'=>$features,
                                                'boxContents'=>$boxContents,
                                                ]);
    }
    public function cartPage()
    {
        $categories = Category::with('subcategories')->get();
        $movingSentence=Sentence::first();
        $cart = session('cart_items', []);
        $cartQuantity = count($cart);
         $productIds = collect($cart)->where('type', 'product')->pluck('id');
    $watchIds = collect($cart)->where('type', 'watch')->pluck('id');
        $products=Product::whereIn('id', $productIds)
                          ->where('is_available',true)
                            ->orderBy('created_at', 'desc') ->get();
                            $watches = Watch::whereIn('id', $watchIds)
        ->where('is_available', true)
        ->get();
        return view('cart', ['movingSentence'=>$movingSentence->sentence,'categories'=>$categories,'cartQuantity'=>$cartQuantity,'products'=>$products,'cart'=>$cart,'watches'=>$watches]);
    }
    public function checkoutPage()
    {
        $movingSentence=Sentence::first();
        $cart = session('cart_items', []);
        $cartQuantity = count($cart);
        $categories = Category::with('subcategories')->get();
        return view('checkout', ['movingSentence'=>$movingSentence->sentence,"cartQuantity"=>$cartQuantity,'categories'=>$categories]);
    }
    public function ThankyouPage(Request $request)
{
    $movingSentence = Sentence::first();
    $cart = session('cart_items', []);
    $cartQuantity = count($cart);
    $categories = Category::with('subcategories')->get();

    $orderNumber = $request->input('orderNumber');
    $order = Order::find($orderNumber);

    // Fetch all order_items for this order
    $orderItems = DB::table('order_items')
        ->where('order_id', $order->id)
        ->get();

    $message = "Name: {$order->name}%0A"
        . "Mobile Number: {$order->mobile}%0A"
        . "Second Mobile Number: {$order->second_mobile}%0A"
        . "City: {$order->city}%0A"
        . "Street: {$order->street}%0A"
        . "Building: {$order->building}%0A"
        . "Floor: {$order->floor}%0A"
        . "Remarks: {$order->remarks}%0A"
        . "Total Price: $" . "{$order->total}%0A%0A"
        . "Items Ordered:%0A";

    foreach ($orderItems as $item) {
        // Check if this row belongs to a product or a watch
        if (!empty($item->product_id)) {
            $product = \App\Models\Product::find($item->product_id);
            if ($product) {
                $message .= "- {$product->name} (Qty: {$item->quantity})%0A";
            }
        } elseif (!empty($item->watch_id)) {
            $watch = \App\Models\Watch::find($item->watch_id);
            if ($watch) {
                $message .= "- {$watch->name} (Qty: {$item->quantity})%0A";
            }
        }
    }

    return view('thankyou', [
        'movingSentence' => $movingSentence->sentence,
        'cartQuantity' => $cartQuantity,
        'categories' => $categories,
        'orderNumber' => $orderNumber,
        'message' => $message
    ]);
}

    public function OrdersPage()
    {
        $orders = Order::where('done', false)
               ->orderBy('created_at', 'desc')
               ->get();

        return view('manageOrders', ['orders'=>$orders]);
    }
    public function OrderPage(order $order)
    {
        if($order->done==false) {
            return view('orderDetails', ['order'=>$order]);

        } else {
            return redirect('/admin');

        }
    }
    public function EditOrderPage(order $order)
    {
        if($order->done==false) {
            return view('editOrder', ['order'=>$order]);

        } else {
            return redirect('/admin/orders');

        }
    }
    public function comingSoonPage()
    {
        $movingSentence=Sentence::first();
        $categories = Category::with('subcategories')->get();
        $cart = session('cart_items', []);
        $cartQuantity = count($cart);
        $comingSoonGames=ComingProduct::paginate(12);
        
        return view('comingSoon', ['movingSentence'=>$movingSentence->sentence,'categories' => $categories,'cartQuantity'=>$cartQuantity,'comingSoonGames'=>$comingSoonGames]);
    }
    public function watchesPage()
    {
        $movingSentence=Sentence::first();
        $categories = Category::with('subcategories')->get();
        $cart = session('cart_items', []);
        $cartQuantity = count($cart);
       $watches=Watch::with('type')
    ->where('type_id','!=',4)->orderBy('created_at', 'desc') ->paginate(12);
       $path='Watches'; 
       return view('watches', ['path'=>$path,'watches'=>$watches,'movingSentence'=>$movingSentence->sentence,'categories' => $categories,'cartQuantity'=>$cartQuantity]);
    }
    public function braceletsPage()
    {
        $movingSentence=Sentence::first();
        $categories = Category::with('subcategories')->get();
        $cart = session('cart_items', []);
        $cartQuantity = count($cart);
       $watches=Watch::with('type')
    ->where('type_id',4)->orderBy('created_at', 'desc') ->paginate(12);
       $path='Bracelets'; 
       return view('watches', ['path'=>$path,'watches'=>$watches,'movingSentence'=>$movingSentence->sentence,'categories' => $categories,'cartQuantity'=>$cartQuantity]);
    }
    public function sentencePage(){
    $sentence=Sentence::first();
        return view('sentenceManage',['sentence'=>$sentence->sentence]);
    }
    public function categoriesPage(){
$categories = Category::with('subcategories')->get();
    return view('categories',['categories'=>$categories]);    
    }
    public function addCategoryPage(){
    return view('addCategory');    
    }
    public function editCategoryPage(Category $category){
    return view('editCategory',['category'=>$category]);    
    }
}