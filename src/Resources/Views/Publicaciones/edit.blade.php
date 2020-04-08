@extends(config('simplecms.layout.backend'))

@section('title')
Editar Publicación
@stop

@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-primary card card-primary">
            <div class="panel-heading panel-heading-with-buttons card-header">
                Editar publicación
            </div>
            <div class="panel-body card-body">

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

@section('footer-scripts')
    <!--    <script>
        $('#nueva-categoria form').modalForm({limpiarFormEnabled: true});
    </script>-->

    <script type="text/javascript">
        $(document).ready(function () {
            $('.summernote.cuerpo').summernote(SummerNoteHelper.defultConfig);
        });
    </script>

@endsection

