@extends(config('simplecms.backend.layout'))

@section('title')
Nueva publicación
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
                Nueva publicación
            </div>
            <div class="panel-body">

                <form action="{{ route('simplecms.publicaciones.store', $publicacion->getId()) }}" class="form-horizontal" method="POST">
                    {{ csrf_field() }}

                    <input type="hidden" value="{{ $publicacion->getId()}}" name="id" />

                    @include('Lebenlabs/SimpleCMS::Publicaciones.form')

                </form>


            </div>
        </div>
    </div>
</div>

@include('Lebenlabs/SimpleCMS::CategoriasPublicacion.modal-form')

@endsection

@section('footer-scripts')
<!--    <script>
        $('#nueva-categoria form').modalForm({limpiarFormEnabled: true});
    </script>-->

@endsection
