@extends(config('simplecms.frontend.layout'))

@section('title')
    Publicaciones en "{{ $categoria }}"
@stop

@section('content')
    <!--  Page Content, class footer-fixed if footer is fixed  -->
    <div id="page-content" class="header-static footer-fixed">
        <!--  Slider  -->
        <div id="flexslider" class="fullpage-wrap extrasmall">
            <ul class="slides">
                <li style="background-image: url({{ asset('img/publicaciones.jpg') }})">
                    <div class="text text-center">
                        <h1 class="white margin-bottom-small">Publicaciones en "{{ $categoria }}"</h1>
                    </div>
                    <div class="gradient dark"></div>
                </li>
            </ul>
        </div>
        <!--  END Slider  -->

        <div id="home-wrap" class="content-section fullpage-wrap grey-background row">
            <div class="col-md-9 padding-leftright-null">
                <!--  News Section  -->
                <section id="news" class="page">

                    <div class="news-items equal one-columns">
                        @foreach ($publicaciones as $i => $publicacion)
                            @include('Lebenlabs/SimpleCMS::Partials.Publicaciones.preview', ['publicacion' => $publicacion])
                        @endforeach
                    </div>

                </section>
                <!--  END News Section  -->
                <!--  Navigation  -->
                <section id="nav" class="padding-top-null grey-background">
                    <div class="row text-center">
                        {!! $publicaciones->links() !!}
                    </div>
                </section>
                <!--  END Navigation  -->
            </div>

            <!--  Right Sidebar  -->
            <div class="col-md-3 text">
                <aside class="sidebar"></aside>
            </div>
            <!--  END Right Sidebar  -->
        </div>
    </div>
    <!--  END Page Content, class footer-fixed if footer is fixed  -->

@endsection