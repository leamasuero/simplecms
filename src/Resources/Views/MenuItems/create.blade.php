@extends(config('simplecms.backend.layout'))

@section('title')
Crear Menu item - Menu {{ $menu }}
@stop

@section('content')

<div class="row">
    <div class="col-md-10 col-md-offset-1">

        <div class="panel panel-primary">
            <div class="panel-heading panel-heading-with-buttons">
                <a class="btn btn-default pull-left" href="{{ route('simplecms.menus.menu_items.index', $menu->getId()) }}" >
                    <i class="fa fa-arrow-circle-left"></i>
                    Volver
                </a>
                &nbsp;
                Crear Menu item - Menu {{ $menu }}

            </div>
            <div class="panel-body">

                <form action="{{ route('simplecms.menus.menu_items.store', [$menu->getId()]) }}" class="form-horizontal" id="menu-item-form" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('post') }}

                    @include('Lebenlabs/SimpleCMS::MenuItems.form')

                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('footer-scripts')
@parent

@endsection