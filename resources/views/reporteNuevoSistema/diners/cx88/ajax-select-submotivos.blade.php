<option value="">--- Seleccione uno ---</option>
@if(!empty($submotivos))
    @foreach($submotivos as $key => $value)
        <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
@endif