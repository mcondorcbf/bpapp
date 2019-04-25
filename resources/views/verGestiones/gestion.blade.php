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
                <form role="form">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="ejemplo_email_1">Gestor</label>
                        <input type="gestor" class="form-control" id="gestor" placeholder="" value="{{$gestion->gestor}}">
                    </div>
                    <button type="submit" class="btn btn-default">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>
