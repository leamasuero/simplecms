@extends(config('simplecms.backend.layout'))

@section('title')
    Archivos de {{ $entidad }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-primary">
                <div class="panel-heading panel-heading-with-buttons">
                    <a class="btn btn-default pull-left" href="{{ $entidad->getIndexRoute() }}" >
                        <i class="fa fa-arrow-circle-left"></i>
                        Volver
                    </a>
                    &nbsp;
                    Cargar nuevos archivos a "{{ $entidad }}"

                </div>
                <div class="panel-body">
                    <form action="{{ route('simplecms.archivos.store', $entidad->getId()) }}" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        {!! csrf_field() !!}

                        <input name="entidad" value="{{ get_class($entidad) }}" type="hidden"/>
                        <input name="entidad_id" value="{{ $entidad->getId() }}" type="hidden"/>

                        <div class="form-group {{ $errors->has('archivos') ? 'has-error':'' }}">
                            <label class="col-md-4 control-label" for="archivos">Archivos</label>
                            <div class="input-group col-md-6">
                                <input id="archivos" name="archivos[]" class="form-control input-md" type="file" multiple>
                                @if($errors->has('archivos'))
                                {!! $errors->first('archivos', '<small class="control-label">:message</small>') !!}
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="atributos[exclusivo]">Exclusivo(s)?</label>
                            <div class="input-group col-md-6">
                                {{ Form::checkbox('atributos[exclusivo]', 1, old('atributos[exclusivo]'), ['id' => 'atributos[exclusivo]']) }}
                            </div>
                            <small>Los archivos exclusivos requieren que el usuario este autenticado y verificado para poder ser descargados.</small>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Aceptar
                                </button>
                                <a href="{{ $entidad->getIndexRoute() }}" class="btn btn-default">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @if(count($archivos))

        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-primary">
                    <div class="panel-heading">Archivos de "{{ $entidad }}"</div>
                    <div class="panel-body">
                        @foreach($archivos as $archivo)
                        <div class="col-md-6 col-md-offset-3 mb5">
                            <form action="{{ route('simplecms.archivos.destroy', $archivo->getId()) }}" method="POST" class="dinline" onsubmit="return confirm('¿Está seguro?')">
                                {!! csrf_field() !!}
                                {!! method_field('delete') !!}
                                <button type="submit" class="btn btn-danger" title="Eliminar este archivo">
                                        <span class="fa fa-trash"></span>
                                </button>
                                <span class="fa fa-file-pdf"></span>
                                <a href="{{ route('simplecms.public.archivos.show', $archivo->getId()) }} ">{{ $archivo }}</a>
                                <div class="clearfix"></div>
                            </form>

                            {{--Resolver esto para bootstrap 3.4 - Toggle de exclusividad --}}
                            {{--<div class="custom-control custom-switch">--}}
                                {{--<input type="checkbox" class="custom-control-input" id="customSwitch1">--}}
                                {{--<label class="custom-control-label" for="customSwitch1">Toggle this switch element</label>--}}
                            {{--</div>                            --}}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

