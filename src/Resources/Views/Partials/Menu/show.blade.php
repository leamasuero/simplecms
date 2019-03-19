@foreach ($rootMenuItems as $rootMenuItem)
    @if ($rootMenuItem->getTieneHijos())
        <li class="submenu">
            <a href="{{ $rootMenuItem->getAccionRoute() }}">{{ $rootMenuItem->getNombre() }}</a>
            <ul class="sub-menu">
                @foreach ($rootMenuItem->getHijos() as $menuItemHijo)
                    @if($menuItemHijo->isVisible())
                        <li><a href="{{ $menuItemHijo->getAccionRoute() }}">{{ $menuItemHijo->getNombre() }}</a></li>
                    @endif
                @endforeach
            </ul>
        </li>
    @else
        <li>
            <a href="{{ $rootMenuItem->getAccionRoute() }}">{{ $rootMenuItem->getNombre() }}</a>
        </li>
    @endif
@endforeach
