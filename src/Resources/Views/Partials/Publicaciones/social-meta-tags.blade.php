<meta property="og:title" content="{{ config('app.name') }} | {{ $entidad }}">
<meta property="og:description" content="{{ $entidad->getMetaDescription() }}">
<meta property="og:image" content="{{ $entidad->getMetaImage() }}">
<meta property="og:url" content="{{ $entidad->getUrl() }}">
<meta name="twitter:card" content="summary_large_image">

<meta property="og:site_name" content="{{ config('app.name') }}">

<meta property="fb:app_id" content="{{ config('services.facebook.app_id') }}">