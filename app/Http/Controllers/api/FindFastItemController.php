<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Item\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FindFastItemController extends Controller
{
    public function FastSearch(string $item_code): ItemResource
    {
        $item = Item::with( 'location')->where('item_code', $item_code)->first();
        return new ItemResource($item);
    }
}
