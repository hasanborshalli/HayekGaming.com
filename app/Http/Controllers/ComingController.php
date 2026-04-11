<?php

namespace App\Http\Controllers;

use App\Models\Coming;
use App\Models\ComingProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ComingController extends Controller
{
    public function editComing(Request $request)
    {
        $currentImage=Coming::first();
        $fields = $request->validate([
        'image' => 'image|mimes:jpeg,png,jpg,webp|max:2048',

]);
        if($request['image']) {
            if ($currentImage->image && Storage::exists('comingSoon/' . $currentImage->image)) {
                Storage::delete('comingSoon/' . $currentImage->image);
            }
            $image=$request->file('image');
            $customName='ComingSoon-'.Str::uuid().'.webp';
            $image->storeAs('comingSoon', $customName);
            $fields['image']=$customName;
        }
        $currentImage->update($fields);
        return redirect('/admin/comingSoon')->with('message', 'Image Updated Successfully');
   
    }
 public function deleteComingProduct(ComingProduct $comingProduct)
 
    {
        if ($comingProduct->image && Storage::exists('comingSoon/' . $comingProduct->image)) {
                    Storage::delete('comingSoon/' . $comingProduct->image);
                }
        
        $comingProduct->delete();
        return response()->json(['status'=>"removed"]);
    }
    public function addComingProduct(Request $request){
        $fields=$request->validate(['image'=>'image|mimes:jpeg,png,jpg,webp|max:2048']);
        $image=$request->file('image');
            $customName='ComingSoon-'.Str::uuid().'.webp';
            $image->storeAs('comingSoon', $customName);
            $fields['image']=$customName;
        ComingProduct::create($fields);
        return redirect('/admin/comingSoon')->with('message', 'Product Addedd Successfully');
    }
     public function editComingProduct(Request $request, ComingProduct $comingProduct){
        $fields=$request->validate(['image'=>'image|mimes:jpeg,png,jpg,webp|max:2048']);
        if($request['image']) {
            if ($comingProduct->image && Storage::exists('comingSoon/' . $comingProduct->image)) {
                Storage::delete('comingSoon/' . $comingProduct->image);
            }
            $image=$request->file('image');
            $customName='ComingSoon-'.Str::uuid().'.webp';
            $image->storeAs('comingSoon', $customName);
            $fields['image']=$customName;
        }
        $comingProduct->update($fields);
        return redirect('/admin/comingSoon')->with('message', 'Product Updated Successfully');
    }
}