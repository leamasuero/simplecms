<fieldset>
    <legend>Datos de la categoría</legend>

    <div class="form-group row {{ $errors->has('nombre') ? 'has-error':'' }}">
        <label class="col-md-2 control-label text-right">Título</label>
        <div class="input-group col-md-9">
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $categoria->getNombre()) }}" autocomplete="off" />
            @if($errors->has('nombre'))
            {!! $errors->first('nombre', '<small class="control-label">:message</small>') !!}
            @endif
        </div>
    </div>

    <div class="form-group row ">
        <label class="col-md-2 control-label text-right" for="destacada">Destacada</label>
        <div class="input-group col-md-9">
            {!! checkbox('destacada', '1', old('destacada', $categoria->isDestacada()), ['id' => 'destacada']) !!}
        </div>
    </div>

    <div class="form-group row ">
        <label class="col-md-2 control-label text-right" for="publicada">Publicada</label>
        <div class="input-group col-md-9">
            {!! checkbox('publicada', '1', old('publicada', $categoria->isPublicada()), ['id' => 'publicada']) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label text-right"></label>
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