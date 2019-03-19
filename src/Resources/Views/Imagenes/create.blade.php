@extends(config('simplecms.backend.layout'))

@section('title')
Imagen de la publicación
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-primary">
                <div class="panel-heading panel-heading-with-buttons">
                    <a class="btn btn-default pull-left" href="{{ route('simplecms.publicaciones.index') }}" >
                        <i class="fa fa-arrow-circle-left"></i>
                        Volver
                    </a>
                    &nbsp;
                    Imágen de "{{ $publicacion }}

                </div>
                <div class="panel-body">

                    <form action="{{ route('simplecms.imagenes.store', $publicacion->getId()) }}" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        {!! csrf_field() !!}

                        <div class="form-group {{ $errors->has('imagen') ? 'has-error':'' }}">
                            <label class="col-md-2 control-label" for="imagen">Imagen</label>
                            <div class="input-group col-md-8">
                                <input id="imagen" name="imagen" class="form-control input-md" type="file">
                                @if($errors->has('imagen'))
                                    {!! $errors->first('imagen', '<small class="control-label">:message</small>') !!}
                                @endif
                                <p class="help-block">Las imágenes deben ser de 1440x600 o proporcional (ancho x alto)</p>
                                <p class="help-block">Cargar una nueva imagen a la publicación descartará la imagen asignada previamente.</p>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Aceptar
                                </button>
                                <a href="{{ route('simplecms.publicaciones.index') }}" class="btn btn-default">Cancelar</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @php $imagen = $publicacion->getImagen() @endphp
    @if($imagen)
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-primary">
                    <div class="panel-heading">Thumbnail de la imagen cargada</div>
                    <div class="panel-body">
                        <div class="thumbnail">
                            <img src="{{ $imagen->getThumbnailUrl() }}">
                            <div class="caption">
                                <form action="{{ route('simplecms.imagenes.destroy', $imagen->getId()) }}" method="POST">
                                    {!! csrf_field() !!}
                                    {!! method_field('delete') !!}
                                    <button type="submit" class="btn btn-danger pull-right">Eliminar</button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
