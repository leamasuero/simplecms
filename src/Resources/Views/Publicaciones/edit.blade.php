@extends(config('simplecms.backend.layout'))

@section('title')
Editar Publicación
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
                Editar publicación
            </div>
            <div class="panel-body">

                <form action="{{ route('simplecms.publicaciones.update', $publicacion->getId()) }}" class="form-horizontal" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('put') }}

                    <input type="hidden" value="{{ $publicacion->getId()}}" name="id" />

                    @include('Lebenlabs/SimpleCMS::Publicaciones.form')

                </form>
            </div>
        </div>
    </div>
</div>


@endsection
