<fieldset>
    <legend>Datos de la publicación</legend>

    <div class="form-group row {{ $errors->has('titulo') ? 'has-error':'' }}">
        <label class="col-md-2 control-label text-right">Título</label>
        <div class="input-group col-md-9">
            <input type="text" name="titulo" id="titulo" class="form-control"
                   value="{{ old('titulo', $publicacion->getTitulo()) }}" autocomplete="off"/>
            @if($errors->has('titulo'))
                {!! $errors->first('titulo', '<small class="control-label text-right">:message</small>') !!}
            @endif
        </div>
    </div>

    <div class="form-group row {{ $errors->has('extracto') ? 'has-error':'' }}">
        <label class="col-md-2 control-label text-right">Extracto</label>
        <div class="col-md-9">
            <textarea name="extracto" id="extracto" class="form-control extracto"
                      rows="5">{{ old('extracto', $publicacion->getExtracto()) }}</textarea>
            @if($errors->has('extracto'))
                {!! $errors->first('extracto', '<small class="control-label text-right">:message</small>') !!}
            @endif
        </div>
    </div>

    <div class="form-group row {{ $errors->has('categoria') ? 'has-error':'' }}">
        <label class="col-md-2 control-label text-right" for="categoria">Categoría</label>
        <div class="input-group col-md-9">
            <div class="form-group col-lg-12">
                {!! select('categoria', ['' => 'Seleccionar...'] + $categorias, old('categoria', optional($publicacion->getCategoria())->getId()), ['class' => 'form-control']) !!}
                @if($errors->has('categoria'))
                    {!! $errors->first('categoria', '<br/><small class="control-label text-right">:message</small>') !!}
                @endif
            </div>
        </div>
    </div>

    <div class="form-group row {{ $errors->has('fecha_publicacion') ? 'has-error':'' }}">
        <label class="col-md-2 control-label text-right">Fecha publicación</label>
        <div class="input-group col-md-9">
            <input type="date" name="fecha_publicacion" id="fecha_publicacion" class="form-control datetimepicker"
                   value="{{ old('fecha_publicacion', optional($publicacion->getFechaPublicacion())->format('Y-m-d')) }}"
                   autocomplete="off"/>
            @if($errors->has('fecha_publicacion'))
                {!! $errors->first('fecha_publicacion', '<small class="control-label text-right">:message</small>') !!}
            @endif
        </div>
    </div>

    <div class="form-group row ">
        <label class="col-md-2 control-label text-right" for="destacada">Destacada</label>
        <div class="input-group col-md-9">
            {!! checkbox('destacada', '1', old('destacada', $publicacion->isDestacada()), ['id' => 'destacada']) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label text-right" for="privada">Privada</label>
        <div class="col-md-10">
            {!! checkbox('privada', '1', old('privada', $publicacion->isPrivada()), ['id' => 'privada']) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label text-right" for="publicada">Publicada</label>
        <div class="input-group col-md-9">
            {!! checkbox('publicada', '1', old('publicada', $publicacion->isPublicada()), ['id' => 'publicada']) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label text-right" for="notificable">
            Notificable
        </label>

        <div class="input-group col-md-9">
            @if($publicacion->isNotificada())
                <input type="hidden" name="notificable" value="1"/>
                <input type="checkbox" checked disabled />
                <div class="ml-3">
                    <small class="form-text text-muted">
                        Publicacion notificada el {{ $publicacion->getNotificadaAt()->format('d/m/Y H:i') }}
                    </small>
                </div>
            @else
                {!! checkbox('notificable', '1', old('notificable', $publicacion->isNotificable()), ['id' => 'notificable']) !!}
                <div class="ml-3">
                    <small class="form-text text-muted">
                        De ser notificable, la publicación será notificada a los profesionales vía mail pasado
                        los {{ config('simplecms.publicaciones.notificaciones.waiting') }} minutos de su creación
                        (siempre y cuando la fecha de publicacion sea la del dia vigente).
                    </small>
                </div>
            @endif
        </div>


    </div>


    <div class="{{ $errors->has('cuerpo') ? 'has-error':'' }}">
        <div>
            <textarea name="cuerpo" id="cuerpo" class="summernote cuerpo"
                      rows="5">{{ old('cuerpo', $publicacion->getCuerpo()) }}</textarea>
            @if($errors->has('cuerpo'))
                {!! $errors->first('cuerpo', '<small class="control-label text-right">:message</small>') !!}
            @endif
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-2 control-label text-right"></label>
        <div class="col-md-9 col-md-offset-4">
            <button type="submit" class="btn btn-primary">
                Aceptar
            </button>
            <a href="{{ route('simplecms.publicaciones.index') }}" class="btn btn-default">Cancelar</a>
        </div>
    </div>

</fieldset>
