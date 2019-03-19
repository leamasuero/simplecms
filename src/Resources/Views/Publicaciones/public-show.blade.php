@extends(config('simplecms.frontend.layout'))

@section('metadescription')
    {!! $publicacion->getMetaDescription() !!}
@stop


@section('head-meta')
    @parent
    @include('Lebenlabs/SimpleCMS::Partials.Publicaciones.social-meta-tags', ['entidad' => $publicacion])
@endsection


@section('title')
    {{ $publicacion }}
@stop

@section('content')

    <!--  Page Content, class footer-fixed if footer is fixed  -->
    <div id="page-content" class="header-static footer-fixed">
        <!--  Slider  -->
        <div id="flexslider" class="fullpage-wrap extrasmall">
            <ul class="slides">

                @php $imagen = $publicacion->getImagen() @endphp
                @if($imagen)
                    <li style="background-image: url({{ $imagen->getUrl() }})">
                @else
                    <li style="background-image: url({{ asset(config('simplecms.default.imagenes.portada_publicacion')) }})">
                @endif

                    <div class="text text-center">
                        <h1 class="white margin-bottom-small">{{ $publicacion->getTitulo() }}</h1>
                    </div>
                    <div class="gradient dark"></div>
                </li>
            </ul>
        </div>
        <!--  END Slider  -->

        <div id="post-wrap" class="content-section fullpage-wrap">
            @include('flash::message')
            <div class="row padding-onlytop-md content-post no-margin">
                <div class="col-md-offset-3 col-md-6 padding-leftright-null">
                    <h1>{{ $publicacion->getTitulo() }}</h1>
                    <span class="category">{{ $publicacion->getCategoria()->getNombre() }}</span>
                    <span class="date">{{ $publicacion->getFechaPublicacionFormat() }}</span>
                </div>

                <div class="col-md-offset-3 col-md-6 padding-leftright-null">
                    {!! $publicacion->getCuerpo() !!}
                </div>
            </div>

            @include('Lebenlabs/SimpleCMS::Partials.Publicaciones.archivos', ['archivos' => $archivos])

            <!-- Post Meta  -->
            <div class="row no-margin">
                <div class="col-md-offset-3 col-md-6 padding-leftright-null">
                    <div id="post-meta">
                        <ul class="tagCloud">
                            <li class="title">Categoría</li>
                            <li>
                                <a href="{{ route('simplecms.public.publicaciones.indexByCategoriaSlug', $publicacion->getCategoria()->getSlug()) }}">
                                    {{ $publicacion->getCategoria() }}
                                </a>
                            </li>
                        </ul>
                        <span class="info">Publicado el <em>{{ $publicacion->getFechaPublicacionFormat() }}</em></span>
                    </div>
                </div>
            </div>
            <!--  END Post Meta  -->
            @include('Lebenlabs/SimpleCMS::Partials.Publicaciones.share-widget', ['url' => $publicacion->getUrl()])
        </div>
    </div>
    <!--  END Page Content, class footer-fixed if footer is fixed  -->
@endsection


