@if(!empty($additionals))
    <ul class="list-group">
        @foreach($additionals as $additional)
            <li class="list-group-item">{{ $additional }}</li>
        @endforeach
    </ul>
@else
    <p class="text-muted">No se han añadido opciones adicionales para este ítem.</p>
@endif
