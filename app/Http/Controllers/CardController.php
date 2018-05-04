<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Type;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use App\Http\Requests\StoreCardRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateCardRequest;

class CardController extends Controller
{
    // Display a listing of the resource
    public function index()
    {
		return view('cards.index')->withCards(
			Card::all()
		);
    }

    // Show the form for creating a new resource
    public function create()
    {
        return view('cards.create')->withTypes(
			Type::orderBy('type')->get()
		);
    }

    // Store a newly created resource in storage
    public function store(StoreCardRequest $request)
    {
		if (Card::count() >= 3) {
			return back()->withError(trans('cards.already_3_cards'));
		}

        $image = $request->file('image');
		$ext = $image->getClientOriginalExtension();
		$filename = getFileName($request->type, $ext);
		
		(new ImageManager)->make($image)->resize(300, 410)->save(
			storage_path('app/public/img/cards/' . $filename
		));

		Card::create([
			'image' => $filename,
			'type_id' => $request->type
		]);

		return redirect('cards')->withSuccess(
			trans('cards.card_added')
		);
    }

    // Show the form for editing the specified resource.
    public function edit(Card $card)
    {
		$types = Type::orderBy('type')->get();
		return view('cards.edit')->with(
			compact('card', 'types')
		);
    }

    // Update the specified resource in storage
    public function update(UpdateCardRequest $request, Card $card)
    {
		if ($request->hasFile('image')) {
			if (Storage::exists('public/img/cards/'.$card->image)) {
				Storage::delete('public/img/cards/'.$card->image);
			}
			$image = $request->file('image');
			$ext = $image->getClientOriginalExtension();
			$filename = getFileName($request->type, $ext);
			
			(new ImageManager)->make($image)->resize(300, 410)->save(
				storage_path('app/public/img/cards/' . $filename
			));
		}
		$card->update([
			'image' => $filename,
			'type_id' => $request->type
		]);

		return redirect('cards')->withSuccess(
			trans('cards.card_changed')
		);
    }

    // Remove the specified resource from storage
    public function destroy(Card $card)
    {
		$card->delete();
		Storage::delete('public/img/cards/'.$card->image);
		return back()->withSuccess(trans('cards.card_deleted'));
    }
}
