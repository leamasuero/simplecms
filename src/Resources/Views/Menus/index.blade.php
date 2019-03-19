@extends(config('simplecms.backend.layout'))

@section('title')
Menus
@stop

@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading">Menus</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $menu)
                    <tr>
                        <th scope="row">
                            {{ $menu->getId() }}
                        </th>
                        <td>
                            {{ $menu }}
                        </td>

                        <td class="text-center">
                            <a class="btn btn-default" href="{{ route('simplecms.menus.menu_items.index', $menu->getId()) }}" title="Ver Menú Items">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection

