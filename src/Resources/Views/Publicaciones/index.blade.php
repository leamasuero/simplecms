@extends(config('simplecms.layout.backend'))

@section('title')
    Publicaciones
@stop

@php ($busquedaWidget = true)

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default card">
                <div class="panel-heading panel-heading-with-buttons card-header">
                    Publicaciones
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th class="text-center">Categoría</th>
                        <th class="text-center">Publicada</th>
                        <th class="text-center">Destacada</th>
                        <th class="text-center">Privada</th>
                        <th class="text-center">Fecha de publicación</th>
                        <th class="text-center w200px">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($publicaciones as $publicacion)
                        <tr>
                            <th scope="row">
                                {{ $publicacion->getId() }}
                            </th>
                            <td>
                                {{ $publicacion }}
                            </td>
                            <td class="text-center">
                                {{ $publicacion->getCategoria() }}
                            </td>
                            <td class="text-center">
                                @if($publicacion->isPublicada())
                                    Si
                                @else
                                    <b>NO</b>
                                @endif
                            </td>
                            <td class="text-center">
                                <i class="fa{{ $publicacion->isDestacada() ? '':'r' }} fa-star" title="Destacada"></i>
                            </td>
                            <td class="text-center">
                                @if ($publicacion->isPrivada())
                                    <b>SI</b>
                                @else
                                    No
                                @endif
                            </td>
                            <td class="text-center">
                                @fecha($publicacion->getFechaPublicacion())
                            </td>
                            <td class="text-center">

                                {{--TODO: esto hay que sacarlo--}}
                                <a class="btn btn-sm btn-secondary"
                                   href="{{ route('simplecms.archivos.create', ['entidad' => get_class($publicacion),'entidad_id' => $publicacion->getId()]) }}"
                                   title="Archivos de la publicación">
                                    <i class="fa fa-file-pdf"></i>
                                </a>
                                <a class="btn btn-sm btn-secondary"
                                   href="{{ route('simplecms.publicaciones.edit', $publicacion->getId()) }}"
                                   title="Editar publicación">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>

                                @if(!$publicacion->isProtegida())
                                    <form id="eliminar-publicacion-form-{{ $publicacion->getId() }}"
                                          action="{{ route('simplecms.publicaciones.destroy', $publicacion->getId()) }}"
                                          method="post" onsubmit="return confirm('¿Esta seguro?')" class="dinline">
                                        {{ method_field('delete') }}
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                title="Eliminar Publicación">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm btn-danger" disabled
                                            title="Eliminar Publicación">
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


