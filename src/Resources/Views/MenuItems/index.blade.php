@extends(config('simplecms.backend.layout'))

@section('title')
Menu Items - {{ $menu }}
@stop

@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading panel-heading-with-buttons">

                <a class="btn btn-primary pull-left" href="{{ route('simplecms.menus.index') }}" title="Volver">
                    <i class="fa fa-arrow-circle-left"></i>
                    Volver
                </a>
                &nbsp;
                Menu Items de <b>{{ $menu }}</b>

                <a class="btn btn-success pull-right mr5" href="{{ route('simplecms.menus.menu_items.create', $menu->getId()) }}">
                    <i class="fa fa-plus"></i>
                    Crear menu item
                </a>

            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Nivel</th>
                        <th>Orden</th>
                        <th>Visible</th>
                        <th>Acción</th>
                        {{--<th>Parámetros</th>--}}
                        {{--<th>Externo</th>--}}
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menuItems as $menuItem)
                        <tr>
                            <th scope="row">
                                {{ $menuItem->getId() }}
                            </th>
                            <td>
                                {{ $menuItem->getNombre() }}
                            </td>
                            <td>
                                {{ $menuItem->getNivel() }}
                            </td>
                            <td>
                                {{ $menuItem->getOrden() }}
                            </td>
                            <td>
                                {{ $menuItem->getVisible() ? 'Si' : 'No' }}
                            </td>
                            <td>
                                @if (!$menuItem->getTieneHijos())
                                    <a target="_blank" href="{{ $menuItem->getAccionRoute() }}">
                                        <i class="fa fa-link"></i>
                                    </a>
                                @endif
                            </td>

                            {{--<td>--}}
                                {{--@if (count($menuItem->getAccionArray()) > 0)--}}
                                    {{--<dl class="dl-horizontal">--}}
                                        {{--@foreach ($menuItem->getAccionArray() as $key => $value)--}}
                                            {{--<dt>{{ $key }}</dt>--}}
                                            {{--<dd>{{ $value }}</dd>--}}
                                        {{--@endforeach--}}
                                    {{--</dl>--}}
                                {{--@endif--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--{{ $menuItem->getExterno() ? 'Si' : 'No' }}--}}
                            {{--</td>--}}

                            <td class="text-center">
                                <a class="btn btn-sm btn-secondary" href="{{ route('simplecms.menus.menu_items.edit', [$menu->getId(), $menuItem->getId()]) }}" title="Editar Menú Item">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>

                                <form action="{{ route('simplecms.menus.menu_items.destroy', [$menu->getId(), $menuItem->getId()]) }}" method="post" class="dinline" onsubmit="return confirm('¿Al eliminar este item de menú se borrarán los que sean sus hijos en caso de existir?')">
                                    {!! method_field('delete') !!}
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-danger" title="Eliminar item">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>

                        @if ($menuItem->getTieneHijos())
                            @foreach($menuItem->getHijos() as $menuItemHijo)
                                <tr class="info">
                                    <th scope="row">
                                        {{ $menuItemHijo->getId() }}
                                    </th>
                                    <td>
                                        <span class="fa fa-arrow-right"></span>
                                        {{ $menuItemHijo->getNombre() }}
                                    </td>
                                    <td>
                                        {{ $menuItemHijo->getNivel() }}
                                    </td>
                                    <td>
                                        {{ $menuItemHijo->getOrden() }}
                                    </td>
                                    <td>
                                        {{ $menuItemHijo->getVisible() ? 'Si' : 'No' }}
                                    </td>
                                    <td>
                                        @if (!$menuItemHijo->getTieneHijos())
                                            <a target="_blank" href="{{ $menuItemHijo->getAccionRoute() }}">
                                                <i class="fa fa-link"></i>
                                            </a>
                                        @endif
                                    </td>
                                    {{--<td>--}}
                                        {{--@if (count($menuItemHijo->getAccionArray()) > 0)--}}
                                            {{--<dl class="dl-horizontal">--}}
                                                {{--@foreach ($menuItemHijo->getAccionArray() as $key => $value)--}}
                                                    {{--<dt>{{ $key }}</dt>--}}
                                                    {{--<dd>{{ json_encode($value ) }}</dd>--}}
                                                {{--@endforeach--}}
                                            {{--</dl>--}}
                                        {{--@endif--}}
                                    {{--</td>--}}
                                    {{--<td>--}}
                                        {{--{{ $menuItemHijo->getExterno() ? 'Si' : 'No' }}--}}
                                    {{--</td>--}}

                                    <td class="text-center">
                                        <a class="btn btn-sm btn-secondary" href="{{ route('simplecms.menus.menu_items.edit', [$menu->getId(), $menuItemHijo->getId()]) }}" title="Editar Menú Item">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>

                                        <form action="{{ route('simplecms.menus.menu_items.destroy', [$menu->getId(), $menuItemHijo->getId()]) }}" method="post" class="dinline" onsubmit="return confirm('¿Al eliminar este item de menú se borrarán los que sean sus hijos en caso de existir?')">
                                            {!! method_field('delete') !!}
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-danger" title="Eliminar item">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    @endforeach

                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection


