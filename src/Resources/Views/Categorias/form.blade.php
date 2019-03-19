<fieldset>
    <legend>Datos de la categoría</legend>

    <div class="form-group {{ $errors->has('nombre') ? 'has-error':'' }}">
        <label class="col-md-2 control-label">Título</label>
        <div class="input-group col-md-9">
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $categoria->getNombre()) }}" autocomplete="off" />
            @if($errors->has('nombre'))
            {!! $errors->first('nombre', '<small class="control-label">:message</small>') !!}
            @endif
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-2 control-label" for="destacada"></label>
        <div class="input-group col-md-9">
            {{ Form::checkbox('destacada', 'true', old('destacada', $categoria->isDestacada()), ['id' => 'destacada']) }}
            &nbsp;&nbsp;<label for="destacada">Destacada</label>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-2 control-label" for="publicada"></label>
        <div class="input-group col-md-9">
            {{ Form::checkbox('publicada', 'true', old('publicada', $categoria->isPublicada()), ['id' => 'publicada']) }}
            &nbsp;&nbsp;<label for="publicada">Publicada</label>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-2 control-label"></label>
        <div class="col-md-9 col-md-offset-4">
            <button type="submit" class="btn btn-primary">
                Aceptar
            </button>
            <a href="{{ route('simplecms.categorias.index') }}" class="btn btn-default">Cancelar</a>
        </div>
    </div>

</fieldset>

@section('footer-scripts')
    @parent
@endsection