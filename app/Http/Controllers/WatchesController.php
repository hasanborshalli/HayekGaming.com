<?php

namespace App\Http\Controllers;

use App\Models\Watch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WatchesController extends Controller
{
    public function addWatch(Request $request){
    $fields=$request->validate([
        'name' => 'required|string',
        'type_id' => 'required|exists:types,id',
        'price' => 'required|numeric|min:0',
        'sale' => 'nullable|numeric|min:0',
        'cost' => 'nullable|numeric|min:0',
        'featured' => 'required|in:yes,no',
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
        'color'=> 'required',
        'color1'=> 'nullable',
        'color2'=> 'nullable',
        'color3'=> 'nullable',
        'color4'=> 'nullable',
        'color5'=> 'nullable',
        'color6'=> 'nullable',    
    ]);
        $mainImage=$request->file('image');
        $customName='Watch-'.Str::uuid().'.webp';
        $mainImage->storeAs('watches', $customName);
        $fields['image']=$customName;

        foreach (['image1', 'image2', 'image3', 'image4','image5','image6'] as $img) {
            if($request[$img]) {
                $customName='Watch-'.Str::uuid().'.webp';
                $request->file($img)->storeAs('watches', $customName);
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
        
        Watch::create($fields);

        return redirect('/admin/watches')->with('message', 'Watch Added Successfully');
   
    }
     public function deleteWatch(Watch $watch)
    {
        foreach ([$watch->image,$watch->image1,$watch->image2,$watch->image3,$watch->image4,$watch->image5,$watch->image6] as $img) {
                if ($watch->$img && Storage::exists('watches/' . $watch->image)) {
                    Storage::delete('watches/' . $watch->image);
                }
        }
        $watch->delete();
        return response()->json(['status'=>"removed"]);
    }
    public function editWatch(Request $request, Watch $watch)
    {
        $fields=$request->validate([
        'type_id' => 'required|exists:types,id',
        'name' => 'required|string',
        'price' => 'required|numeric|min:0',
        'sale' => 'nullable|numeric|min:0',
        'cost' => 'nullable|numeric|min:0',
        'featured' => 'required|in:yes,no',
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
        'color'=> 'required',
        'color1'=> 'nullable',
        'color2'=> 'nullable',
        'color3'=> 'nullable',
        'color4'=> 'nullable',
        'color5'=> 'nullable',
        'color6'=> 'nullable', 

        ]);
        foreach (['image','image1', 'image2', 'image3', 'image4','image5','image6'] as $img) {
            if($request[$img]) {
                if ($watch->$img && Storage::exists('watches/' . $watch->$img)) {
                    Storage::delete('watches/' . $watch->$img);
                }
                $customName='Watch-'.Str::uuid().'.webp';
                $request->file($img)->storeAs('watches', $customName);
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
       
        
        if($fields['is_available']=='yes') {
            $fields['is_available']=true;
        } else {
            $fields['is_available']=false;
        }
        $watch->update($fields);
        return redirect('/admin/watches')->with('message', 'Watch Edited Successfully');

    }
    public function adminWatchesSearch(Request $request)
    {
        $fields=$request->validate([
           'search'=>['required','max:255']
        ]);
        $watches=Watch::search($fields['search']) ->orderBy('created_at', 'desc') ->paginate(12);
        return view('watchesManage', ['watches'=>$watches]);
    }
}