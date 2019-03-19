<fieldset>
    <legend>Datos de la publicación</legend>

    <div class="form-group {{ $errors->has('titulo') ? 'has-error':'' }}">
        <label class="col-md-2 control-label">Título</label>
        <div class="input-group col-md-9">
            <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo', $publicacion->getTitulo()) }}" autocomplete="off" />
            @if($errors->has('titulo'))
            {!! $errors->first('titulo', '<small class="control-label">:message</small>') !!}
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('extracto') ? 'has-error':'' }}">
        <label class="col-xs-2 control-label">Extracto</label>
        <div class="input-group col-xs-9">
            <textarea name="extracto" id="extracto" class="form-control extracto" rows="5">{{ old('extracto', $publicacion->getExtracto()) }}</textarea>
            @if($errors->has('extracto'))
            {!! $errors->first('extracto', '<small class="control-label">:message</small>') !!}
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('categoria') ? 'has-error':'' }}">
        <label class="col-md-2 control-label" for="categoria">Categoría</label>
        <div class="input-group col-md-9">
            <div class="form-group col-lg-12">
                {!! Form::select('categoria', ['' => 'Seleccionar...'] + $categorias, old('categoria', $publicacion->getCategoriaId()), ['class' => 'form-control']) !!}
                @if($errors->has('categoria'))
                {!! $errors->first('categoria', '<br/><small class="control-label">:message</small>') !!}
                @endif
            </div>
        </div>
    </div>

    <div class="form-group {{ $errors->has('fecha_publicacion') ? 'has-error':'' }}">
        <label class="col-md-2 control-label">Fecha publicación</label>
        <div class="input-group col-md-9">
            <input type="text" name="fecha_publicacion" id="fecha_publicacion" class="form-control datetimepicker" value="{{ old('fecha_publicacion', $publicacion->getFechaPublicacionFormat()) }}" autocomplete="off" />
            @if($errors->has('fecha_publicacion'))
            {!! $errors->first('fecha_publicacion', '<small class="control-label">:message</small>') !!}
            @endif
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-2 control-label" for="destacada"></label>
        <div class="input-group col-md-9">
            {{ Form::checkbox('destacada', 'true', old('destacada', $publicacion->isDestacada()), ['id' => 'destacada']) }}
            &nbsp;&nbsp;<label for="destacada">Destacada</label>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-2 control-label" for="publicada"></label>
        <div class="input-group col-md-9">
            {{ Form::checkbox('publicada', 'true', old('publicada', $publicacion->isPublicada()), ['id' => 'publicada']) }}
            &nbsp;&nbsp;<label for="publicada">Publicada</label>
        </div>
    </div>

    <div class="form-group {{ $errors->has('cuerpo') ? 'has-error':'' }}">
        <label class="col-xs-2 control-label">Cuerpo</label>
        <div class="input-group col-xs-9">
        </div>
    </div>

    <div class="pl10 pr10 form-group {{ $errors->has('cuerpo') ? 'has-error':'' }}">
        <div class="input-group col-md-offset-2 col-md-8 padding-leftright-null">
            <textarea name="cuerpo" id="cuerpo" class="form-control summernote cuerpo" rows="5">{{ old('cuerpo', $publicacion->getCuerpo()) }}</textarea>
            @if($errors->has('cuerpo'))
            {!! $errors->first('cuerpo', '<small class="control-label">:message</small>') !!}
            @endif
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-2 control-label"></label>
        <div class="col-md-9 col-md-offset-4">
            <button type="submit" class="btn btn-primary">
                Aceptar
            </button>
            <a href="{{ route('simplecms.publicaciones.index') }}" class="btn btn-default">Cancelar</a>
        </div>
    </div>

</fieldset>

@section('footer-scripts')
    @parent
    <script type="text/javascript">
        $(document).ready(function() {
            $('.summernote.cuerpo').summernote(SummerNoteHelper.defultConfig);

            $('.datetimepicker').datetimepicker({
                format: '{{ config("simplecms.formats.moment-just-date") }}'
            });
        });
    </script>
@endsection