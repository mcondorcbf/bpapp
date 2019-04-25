@extends('layouts.app')
@section('scripts')
    <script src="/js/app.js"></script>
@endsection
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <form class="form-horizontal" role="form" method="post" action="{{ url('/busqueda') }}">
                    {{ csrf_field() }}
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    Ingrese el numero de cédula que desea buscar
                    <br>
                    <br>
                        <label for="c.i" class="col-md-1 control-label">Cédula</label>
                        <div class="col-md-6" style="margin-bottom: 10px">
                            <input id="c.i" type="number" class="form-control" name="c.i" value="{{ old('c.i') }}" required autofocus>
                        </div>
                    <div class="form-group">
                        <div class="col-md-1 col-md-offset-0 col-xs-5 col-xs-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Buscar
                            </button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection