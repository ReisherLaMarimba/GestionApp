<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class AvailablesItemsApi extends Controller
{
    public function getAvailablesItems(Request $request)
    {
        $category = $request->input('category', null); // Get the category from the request
        $search = $request->input('q', ''); // Get the search term
        $filters = $request->input('filters', []); // Additional filters

        // Initialize the query
        $query = Item::query();

        // Filter by category if provided
        if (!empty($category)) {
            $query->whereHas('category', function ($query) use ($category) {
                $query->where('name', '=', ucfirst($category)); // Match the category name
            });
        }

        // Filter items that have less than 2 users assigned
        $query->whereDoesntHave('users', function ($subquery) {
            $subquery->select('item_user.item_id')
                ->groupBy('item_user.item_id')
                ->havingRaw('COUNT(item_user.user_id) >= 2'); // Filter items already assigned to 2 or more users
        });

        // Apply search term to filter by item_code
        if (!empty($search)) {
            $query->where('item_code', 'LIKE', "%{$search}%");
        }

        // Apply any additional filters from the request
        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }

        // Get the results with the necessary fields
        $items = $query->get(['id', 'item_code as text']); // Format for dropdown

        return response()->json($items); // Return as JSON response

    }
}
