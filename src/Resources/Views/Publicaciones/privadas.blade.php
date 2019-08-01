@extends(config('simplecms.backend.layout'))

@section('title')
Publicaciones privadas
@stop

@php ($busquedaWidget = true)

@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading panel-heading-with-buttons">
                Publicaciones

                <a class="btn btn-success pull-right mr5" href="{{ route('simplecms.publicaciones.create') }}">
                    <i class="fa fa-plus"></i>
                    Crear Publicación
                </a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th class="text-center">Publicada</th>
                        <th class="text-center">Destacada</th>
                        <th class="text-center">Privada</th>
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
                            <a target="_blank" href="{{ route('simplecms.public.publicaciones.show', ['slug' => $publicacion->getSlug()]) }}">
                                {{ $publicacion }}
                            </a>
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
                            <i class="fa {{ $publicacion->isPrivada() ? 'fa-eye-slash':'fa-eye' }}" title="Privada"></i>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-sm btn-default" href="{{route('simplecms.imagenes.create', $publicacion->getId())}}" title="Administrar imagen de la publicación">
                                <i class="fa fa-image"></i>
                            </a>

                            {{--TODO: esto hay que sacarlo--}}
                            <a class="btn btn-sm btn-default" href="{{ route('simplecms.archivos.create', ['entidad' => get_class($publicacion),'entidad_id' => $publicacion->getId()]) }}" title="Archivos de la publicación">
                                <i class="fa fa-file-pdf"></i>
                            </a>
                            <a class="btn btn-sm btn-default" href="{{ route('simplecms.publicaciones.edit', $publicacion->getId()) }}" title="Editar publicación">
                                <i class="fa fa-pencil-alt"></i>
                            </a>

                            @if (!$publicacion->isProtegida())
                            <form id="eliminar-publicacion-form-{{ $publicacion->getId() }}" action="{{ route('simplecms.publicaciones.destroy', $publicacion->getId()) }}" method="post" onsubmit="return confirm('¿Esta seguro?')" class="dinline">
                                {{ method_field('delete') }}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar Publicación">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                            @else
                            <a class="btn btn-sm btn-default disabled" href="javascript::void(0)" title="Esta publicación se encuentra protegida y no puede ser borrada">
                                <i class="fa fa-trash" title="Esta publicación se encuentra protegida y no puede ser borrada"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <div class="text-center">
            {!! $publicaciones->links() !!}
        </div>

    </div>
</div>
@endsection


