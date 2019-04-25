<option>--- Seleccione uno ---</option>
@if(!empty($accion))
    @foreach($accion as $key => $value)
        <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
@endif