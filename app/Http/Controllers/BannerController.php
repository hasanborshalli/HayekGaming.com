<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function addBanner(Request $request)
    {
        $fields = $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:1024',
        'mobile_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:1024',
        'small_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:1024',
        'product_id' => 'required|exists:products,id',
]);
        
        $image=$request->file('image');
        $customName='Banner-'.Str::uuid().'.webp';
        $image->storeAs('banners', $customName);
        $fields['image']=$customName;
        
        $mobileImage=$request->file('mobile_image');
        $customName='MobileBanner-'.Str::uuid().'.'.$mobileImage->getClientOriginalExtension();
        $mobileImage->storeAs('banners', $customName);
        $fields['mobile_image']=$customName;
        
        $smallImage=$request->file('small_image');
        $customName='SmallBanner-'.Str::uuid().'.'.$smallImage->getClientOriginalExtension();
        $smallImage->storeAs('banners', $customName);
        $fields['small_image']=$customName;
        
        Banner::create($fields);
        return redirect('/admin/banners')->with('message', 'Banner Added Successfully');
    }
    public function editBanner(Request $request, Banner $banner)
    {
        $fields = $request->validate([
        'image' => 'image|mimes:jpeg,png,jpg,webp|max:1024',
        'mobile_image' => 'image|mimes:jpeg,png,jpg,webp|max:1024',
        'small_image' => 'image|mimes:jpeg,png,jpg,webp|max:1024',
        'product_id' => 'required|exists:products,id',
]);
        if($request['image']) {
            if ($banner->image && Storage::exists('banners/' . $banner->image)) {
                Storage::delete('banners/' . $banner->image);
            }
            $image=$request->file('image');
            $customName='Banner-'.Str::uuid().'.webp';
            $image->storeAs('banners', $customName);
            $fields['image']=$customName;
        }
        
        if($request['mobile_image']) {
            if ($banner->mobile_image && Storage::exists('banners/' . $banner->mobile_image)) {
                Storage::delete('banners/' . $banner->mobile_image);
            }
            $mobileImage=$request->file('mobile_image');
            $customName='MobileBanner-'.Str::uuid().'.webp';
            $mobileImage->storeAs('banners', $customName);
            $fields['mobile_image']=$customName;
        }
         if($request['small_image']) {
            if ($banner->small_image && Storage::exists('banners/' . $banner->small_image)) {
                Storage::delete('banners/' . $banner->small_image);
            }
            $smallImage=$request->file('small_image');
            $customName='SmallBanner-'.Str::uuid().'.webp';
            $smallImage->storeAs('banners', $customName);
            $fields['small_image']=$customName;
        }
        $banner->update($fields);
        return redirect('/admin/banners')->with('message', 'Banner Updated Successfully');
    }
    public function deleteBanner(Banner $banner)
    {
        if ($banner->mobile_image && Storage::exists('banners/' . $banner->mobile_image)) {
                Storage::delete('banners/' . $banner->mobile_image);
            }
            if ($banner->image && Storage::exists('banners/' . $banner->image)) {
                Storage::delete('banners/' . $banner->image);
            }
        $banner->delete();
        return response()->json(['status'=>"removed"]);
    }
}