@extends(config('simplecms.layout.backend'))

@section('title')
    Nueva publicación
@stop



@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-primary card card-primary">
                <div class="panel-heading panel-heading-with-buttons card-header">
                    Nueva publicación
                </div>
                <div class="panel-body card-body">

                    <form action="{{ route('simplecms.publicaciones.store', $publicacion->getId()) }}"
                          class="form-horizontal" method="POST">
                        {{ csrf_field() }}

                        <input type="hidden" value="{{ $publicacion->getId()}}" name="id"/>

                        @include('Lebenlabs/SimpleCMS::Publicaciones.form')

                    </form>


                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer-scripts')

    <script type="text/javascript">
        $(document).ready(function () {
            $('.summernote.cuerpo').summernote(SummerNoteHelper.defultConfig);
        });
    </script>

@endsection


