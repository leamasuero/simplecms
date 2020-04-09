@extends(config('simplecms.layout.backend'))

@section('title')
Editar categoría
@stop

@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-primary card">
            <div class="panel-heading panel-heading-with-buttons card-header">
                Editar categoría
            </div>
            <div class="panel-body card-body">

                <form action="{{ route('simplecms.categorias.update', $categoria->getId()) }}" class="form-horizontal" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('put') }}

                    <input type="hidden" value="{{ $categoria->getId()}}" name="id" />

                    @include('Lebenlabs/SimpleCMS::Categorias.form')

                </form>
            </div>
        </div>
    </div>
</div>


@endsection
