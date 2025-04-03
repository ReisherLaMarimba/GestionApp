<div class="rounded bg-white mb-3 p-3">
    <div class="border-dashed d-flex align-items-center w-100 rounded overflow-hidden" style="min-height: 250px;">
        {{-- Decodifica el JSON --}}
        @php
            $images = json_decode($item->images, true); // Convierte el JSON a un array
        @endphp

        {{-- Asegúrate de que el array no esté vacío --}}
        @if(!empty($images) && isset($images[0]))
            <img src="{{ asset('storage/' . $images[0]) }}" alt="sample" class="mw-100 d-block img-fluid rounded-1 w-100">
        @else
            <p>No image available</p>
        @endif
    </div>
</div>
