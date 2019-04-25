<!-- Styles -->
<link href="/css/app.css" rel="stylesheet">
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.min.css"/>

<!-- Scripts -->
<script src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/jquery-1.12.4.js"></script>

<div class="form-group">
    <div class="tab-content tab-content-border" style="font-size: 10px">
        <div class="tab-pane fade active in" id="dinersconsolidado">
            <div class="col-md-12 col-lg-12">
                <form class="form-horizontal" role="form" method="post" action="{{ url('/observacion/'.$gestion->id) }}" style="font-size: 10px">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <br>
                        <label >GESTIÓN: {{$gestion->id}}</label><br>
                        <label >GESTOR: {{$gestion->gestor}}</label><br>
                        <label >OBSERVACIONES: <?php echo str_replace ( '//' , '<br>- ' , $gestion->observacion );?> </label><br>
                        <input name="id" type="hidden" value="{{$gestion->id}}">
                        <textarea class="form-control" id="observacion" name="observacion"></textarea>
                    </div>
                    <button type="submit" class="btn btn-default">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</div>
