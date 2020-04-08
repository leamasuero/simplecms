<li class="nav-item dropdown">
    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
        {{ __('Lebenlabs/SimpleCMS::partials.shortcut_simplecms') }} <span class="caret"></span>
    </a>
    <div class="dropdown-menu" role="menu">
        <a class="dropdown-item" href="{{ route('simplecms.publicaciones.index') }}">
            {{ __('Lebenlabs/SimpleCMS::partials.shortcut_publicaciones_index') }}
        </a>
        <a class="dropdown-item" href="{{ route('simplecms.publicaciones.index', ['privada' => true]) }}">
            {{ __('Lebenlabs/SimpleCMS::partials.shortcut_publicaciones_privadas_index') }}
        </a>
        <a class="dropdown-item" href="{{ route('simplecms.publicaciones.create') }}">
            {{ __('Lebenlabs/SimpleCMS::partials.shortcut_publicaciones_create') }}
        </a>
        <div role="separator" class="divider dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route('simplecms.categorias.index') }}">
            {{ __('Lebenlabs/SimpleCMS::partials.shortcut_categorias_index') }}
        </a>
        <a class="dropdown-item" href="{{ route('simplecms.categorias.create') }}">
            {{ __('Lebenlabs/SimpleCMS::partials.shortcut_categorias_create') }}
        </a>
    </div>
</li>