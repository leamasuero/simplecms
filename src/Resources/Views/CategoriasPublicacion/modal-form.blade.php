<div class="modal fade" id="nueva-categoria" tabindex="-1" role="dialog">

    {!! Form::open(['route' => ['simplecms.categorias.ajaxStore'], 'class' => 'form-horizontal ']) !!}

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Nueva Categoría</h4>
            </div>
            <div class="modal-body">

                <div id="modal-msje" class="hidden"></div>

                <div class="form-group">
                    <label class="col-md-4 control-label" for="nombre">Nombre</label>
                    <div class="input-group col-md-6">
                        <input id="nombre" name="nombre" class="form-control input-md" type="text" value="">
                        <small class="control-label"></small>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Aceptar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>

    {!! Form::close() !!}
</div>