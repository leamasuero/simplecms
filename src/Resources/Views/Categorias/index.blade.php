@extends(config('simplecms.backend.layout'))

@section('title')
Categorías
@stop

@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading panel-heading-with-buttons">
                Categorías

                <a class="btn btn-success pull-right mr5" href="{{ route('simplecms.categorias.create') }}">
                    <i class="fa fa-plus"></i>
                    Crear categoría
                </a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th class="text-center">Publicada</th>
                        <th class="text-center">Destacada</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categorias as $categoria)
                    <tr>
                        <th scope="row">
                            {{ $categoria->getId() }}
                        </th>
                        <td>
                            {{ $categoria }}
                        </td>
                        <td class="text-center">
                            @if($categoria->isPublicada())
                                Si
                            @else
                                <b>NO</b>
                            @endif
                        </td>
                        <td class="text-center">
                            <i class="fa{{ $categoria->isDestacada() ? '':'r' }} fa-star" title="Destacada"></i>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-default" href="{{ route('simplecms.categorias.edit', $categoria->getId()) }}" title="Editar categoría">
                                <i class="fa fa-pencil-alt"></i>
                            </a>
                            @if (!$categoria->isProtegida())
                            <form action="{{ route('simplecms.categorias.destroy', $categoria->getId()) }}" method="post" class="dinline" onsubmit="return confirm('¿Esta seguro?')">
                                {!! method_field('delete') !!}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger" title="Eliminar categoría">
                                    <i class="fa fa-trash"></i>
                                </button>
                             </form>
                            @else
                            <a class="btn btn-default disabled" href="javascript::void(0)" title="Esta categoría se encuentra protegida y no puede ser borrada">
                                <i class="fa fa-trash" title="Esta categoría se encuentra protegida y no puede ser borrada"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <div class="text-center">
            {!! $categorias->links() !!}
        </div>

    </div>
</div>
@endsection


