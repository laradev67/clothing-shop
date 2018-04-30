<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use Illuminate\Support\Facades\Storage;

class ApiItemController extends Controller
{

	
    public function index($category = null)
    {
		$statement = $category ? [['category', $category]] : [['category', '!=', 'null']];

		$items = Item::where($statement)->paginate(40);

		return ItemResource::collection($items);
    }


	public function create()
    {
        //
    }


    public function store(Request $request)
    {
		$id = $request->item_id;
		$item = $request->isMethod('put') ? Item::findOrFail($id) : new Item;

		$item->id = $request->input('item_id');
		$item->title = $request->input('title');
		$item->content = $request->input('content');
		$item->sex = $request->input('sex');
		$item->category = $request->input('category');
		$item->price = $request->input('price');
		$item->save();

		return new ItemResource($item);
    }


    public function show(Item $item)
    {
		return new ItemResource($item);
	}
	

    public function random()
    {
		$items = Item::inRandomOrder()->take(6)->get();
		return ItemResource::collection($items);
	}


    public function popular()
    {
		$items = Item::take(12)->latest()->get();
		return ItemResource::collection($items);
    }


    public function destroy($id)
    {
		$item = Item::findOrFail($id);

		if ($item->delete()) {
			Storage::delete('public/img/clothes/'.$item->image);
			return new ItemResource($item);
		}
    }
}
