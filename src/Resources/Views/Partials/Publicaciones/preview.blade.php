@php $imagen = $publicacion->getImagen() @endphp
<div class="single-news one-item horizontal-news">
    <article>
        <div class="col-md-6 padding-leftright-null">
            @if($imagen)
            <div class="image" style="background-image:url({{ $imagen->getThumbnailUrl() }})"></div>
            @else
            <div class="image" style="background-image:url({{ asset(config('cbsf.default.imagenes.preview_publicacion')) }})"></div>
            @endif
        </div>
        <div class="col-md-6 padding-leftright-null">
            <div class="content">
                <span class="read">
                    <i class="material-icons">subject</i>
                </span>
                <h3>{{ $publicacion->getTitulo() }}</h3>
                <span class="category">{{ $publicacion->getCategoria()->getNombre() }}</span>
                <span class="date">{{ $publicacion->getFechaPublicacionFormat() }}</span>
                <p>{{ $publicacion->getExtracto() }}</p>
            </div>
        </div>
        <a href="{{ route('simplecms.public.publicaciones.show', ['slug' => $publicacion->getSlug()]) }}" class="link"></a>
    </article>
</div>