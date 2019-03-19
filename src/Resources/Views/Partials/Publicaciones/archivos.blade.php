@if(count($archivos))
<div class="row no-margin">
    <div class="col-md-offset-3 col-md-6 padding-leftright-null">
        <h4>Documentos relacionados</h4>
        <div>
            <ul class="fsize-15">
                @foreach($archivos as $archivo)
                <li>
                    <span class="fa fa-file-pdf-o"></span>
                    <a href="{{ route('simplecms.public.archivos.show', ['id' => $archivo->getId()]) }}">
                        {{ $archivo }}
                    </a>
                </li>
                @endforeach                    
            </ul>
        </div>
    </div>
</div>
@endif