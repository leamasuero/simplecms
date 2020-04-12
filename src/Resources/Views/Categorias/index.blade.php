@extends(config('simplecms.layout.backend'))

@section('title')
    Categorías
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default card">
                <div class="panel-heading panel-heading-with-buttons card-header">
                    Categorías
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
                                <a class="btn btn-sm btn-secondary"
                                   href="{{ route('simplecms.categorias.edit', $categoria->getId()) }}"
                                   title="Editar categoría">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                                @if (!$categoria->isProtegida())
                                    <form action="{{ route('simplecms.categorias.destroy', $categoria->getId()) }}"
                                          method="post" class="dinline" onsubmit="return confirm('¿Esta seguro?')">
                                        {!! method_field('delete') !!}
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar categoría">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm btn-danger" title="Eliminar categoría" disabled>
                                        <i class="fa fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            <div class="text-center pt-3">
                {!! $paginatorView !!}
            </div>

        </div>
    </div>
@endsection


