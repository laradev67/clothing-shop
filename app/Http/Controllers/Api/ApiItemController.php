<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Traits\ItemHelpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Database\QueryException;

class ApiItemController extends Controller
{
    use ItemHelpers;

    /**
     * @param null|string $category
     * @param null|string $type
     * @return \App\Http\Resources\ItemResource
     */
    public function index($category = null, $type = null)
    {
        if ($category && !$type) {
            $query = [['category', $category]];
        } elseif ($category && $type) {
            $query = [['category', $category], ['type_id', $type]];
        } else {
            $query = [];
        }

        try {
            return ItemResource::collection(
                Item::where($query)
                    ->inStock()
                    ->latest()
                    ->paginate(20)
            );
        } catch (QueryException $e) {
            logs()->error($e->getMessage());
            return collect();
        }
    }

    /**
     * @param string $slug
     * @return \App\Http\Resources\ItemResource
     */
    public function show(string $slug)
    {
        return new ItemResource(Item::whereSlug($slug)->first());
    }

    /**
     * Get list of random items
     *
     * @param string|null $category
     * @param int $visitor_id
     */
    public function random(int $visitor_id, ?string $category = null)
    {
        try {
            if ($category) {
                $items = Item::getRandomUnseen($visitor_id, $category);
            } else {
                $items = Item::getRandomUnseen($visitor_id);
            }
            return ItemResource::collection($items);
        } catch (QueryException $e) {
            return collect();
            logs()->error($e->getMessage());
        }
    }

    /**
     * Get list of popular items
     */
    public function popular()
    {
        try {
            $items = Item::inStock()
                ->take(18)
                ->orderBy('popular', 'desc')
                ->get();
            return ItemResource::collection($items);
        } catch (QueryException $e) {
            logs()->error($e->getMessage());
            return collect();
        }
    }

    /**
     * @param string $slug
     */
    public function destroy(string $slug)
    {
        cache()->forget('footer_latest');

        try {
            $item = Item::whereSlug($slug)->first();
        } catch (QueryException $e) {
            logs()->error($e->getMessage());
            return [];
        }

        cache()->forget('categories_men');
        cache()->forget('categories_women');

        $this->deleteOldPhotos($item->photos);

        $item->views()->delete();
        $item->photos()->delete();

        if ($item->delete()) {
            return response(['status' => 'ok'], 200);
        } else {
            return response(['status' => 'error'], 417);
        }

    }
}
