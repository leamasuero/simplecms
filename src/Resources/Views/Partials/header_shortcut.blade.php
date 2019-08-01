<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
        {{ __('Lebenlabs/SimpleCMS::partials.shortcut_simplecms') }}  <span class="caret"></span>
    </a>

    <ul class="dropdown-menu" role="menu">
        <li>
            <a href="{{ route('simplecms.menus.index') }}">
                {{ __('Lebenlabs/SimpleCMS::partials.shortcut_menus_index') }}
            </a>
        </li>
        <li role="separator" class="divider"></li>
        <li>
            <a href="{{ route('simplecms.publicaciones.index') }}">
                {{ __('Lebenlabs/SimpleCMS::partials.shortcut_publicaciones_index') }}
            </a>
        </li>
        <li>
            <a href="{{ route('simplecms.publicaciones.index', ['privada' => true]) }}">
                {{ __('Lebenlabs/SimpleCMS::partials.shortcut_publicaciones_privadas_index') }}
            </a>
        </li>
        <li>
            <a href="{{ route('simplecms.publicaciones.create') }}">
                {{ __('Lebenlabs/SimpleCMS::partials.shortcut_publicaciones_create') }}
            </a>
        </li>
        <li role="separator" class="divider"></li>
        <li>
            <a href="{{ route('simplecms.categorias.index') }}">
                {{ __('Lebenlabs/SimpleCMS::partials.shortcut_categorias_index') }}
            </a>
        </li>
        <li>
            <a href="{{ route('simplecms.categorias.create') }}">
                {{ __('Lebenlabs/SimpleCMS::partials.shortcut_categorias_create') }}
            </a>
        </li>
    </ul>
</li>