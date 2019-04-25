<option>Seleccione Uno</option>
@if(!empty($productos))
    @foreach($productos as $key => $value)
        <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
@endif