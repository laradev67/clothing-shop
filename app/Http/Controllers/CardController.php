<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Models\Card;
use App\Models\Type;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class CardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    // Display a listing of the resource
    public function index()
    {
        return view('cards.index', [
            'cards' => Card::get(),
        ]);
    }

    // Show the form for creating a new resource
    public function create()
    {
        return view('cards.create', [
            'types' => Type::orderBy('name')->get(),
        ]);
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

        $this->makeCardImage($image, $filename);

        Card::create([
            'image' => $filename,
            'type_id' => $request->type,
            'category' => $request->category,
        ]);

        cache()->forget('home_cards');

        return redirect('cards')->withSuccess(
            trans('cards.card_added')
        );
    }

    // Show the form for editing the specified resource.
    public function edit(Card $card)
    {
        $types = Type::orderBy('name')->get();

        return view('cards.edit')->with(
            compact('card', 'types')
        );
    }

    // Update the specified resource in storage
    public function update(UpdateCardRequest $request, Card $card)
    {
        if ($request->hasFile('image')) {
            if ($card->image != 'default.jpg') {
                Storage::delete('public/img/cards/' . $card->image);
            }
            $image = $request->file('image');
            $ext = $image->getClientOriginalExtension();
            $filename = getFileName($request->type, $ext);

            $this->makeCardImage($image, $filename);

            $card->update(['image' => $filename]);
        }

        $card->update([
            'type_id' => $request->type,
            'category' => $request->category,
        ]);

        cache()->forget('home_cards');

        return redirect('cards')->withSuccess(
            trans('cards.card_changed')
        );
    }

    /**
     * Remove the specified resource from storage,
     * App\Observers\CardObserver will delete image
     * file while database record is being deleted
     */
    public function destroy(Card $card)
    {
        cache()->forget('home_cards');

        return ($card->delete())
        ? redirect('cards')->withSuccess(trans('cards.card_deleted'))
        : redirect('cards')->withError(trans('cards.deleted_fail'));
    }

    /**
     * @param \Illuminate\Http\UploadedFile $image_inst
     * @param string $filename
     * @return void
     */
    public function makeCardImage(UploadedFile $image_inst, string $filename): void
    {
        (new ImageManager)
            ->make($image_inst)
            ->fit(451, 676, function ($constraint) {
                $constraint->upsize();
            }, 'top')
            ->save(storage_path("app/public/img/cards/{$filename}"));
    }
}
