<option>Seleccione Uno</option>
@if(!empty($campanas))
    @foreach($campanas as $key => $value)
        <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
@endif