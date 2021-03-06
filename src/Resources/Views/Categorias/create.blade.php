@extends(config('simplecms.backend.layout'))

@section('title')
Nueva categoría
@stop

@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-primary">
            <div class="panel-heading panel-heading-with-buttons">
                <a class="btn btn-default pull-left" href="{{ route('simplecms.categorias.index') }}" >
                    <i class="fa fa-arrow-circle-left"></i>
                    Volver
                </a>
                &nbsp;
                Nueva categoría
            </div>
            <div class="panel-body">

                <form action="{{ route('simplecms.categorias.store', $categoria->getId()) }}" class="form-horizontal" method="POST">
                    {{ csrf_field() }}

                    <input type="hidden" value="{{ $categoria->getId()}}" name="id" />

                    @include('Lebenlabs/SimpleCMS::Categorias.form')

                </form>
            </div>
        </div>
    </div>
</div>
@endsection