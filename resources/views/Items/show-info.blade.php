<div class="rounded bg-white mb-3 p-3">
    <div class="border-dashed d-flex flex-column w-100 rounded p-3" style="min-height: 250px;">
        <p><strong>Description:</strong> {{ $item->description ?? 'No description available' }}</p>
        <p><strong>Item Code:</strong> {{ $item->item_code ?? 'No code available' }}</p>
        <p><strong>Weight:</strong> {{ $item->weight ? $item->weight . ' kg' : 'N/A' }}</p>
        <p><strong>Stock:</strong> {{ $item->stock ?? 'N/A' }}</p>
        <p><strong>Minimum Quantity:</strong> {{ $item->min_quantity ?? 'N/A' }}</p>
        <p><strong>Maximum Quantity:</strong> {{ $item->max_quantity ?? 'N/A' }}</p>
        <p><strong>Comments:</strong> {{ $item->comments ?? 'No comments available' }}</p>
        <p><strong>Status:</strong> {{ $item->status ?? 'Unknown' }}</p>
        <p><strong>Category:</strong> {{ $item->category->name ?? 'Unknown Category' }}</p>
        <p><strong>Location:</strong> {{ $item->location->name ?? 'Unknown Location' }}</p>
    </div>
</div>
