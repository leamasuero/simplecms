<fieldset>

    <div class="form-group">
        <label class="col-xs-4 control-label">Menú</label>
        <div class="input-group col-xs-6">
            <input type="text" class="form-control readonly" readonly="readonly" value="{{ $menu }}"/>
        </div>
    </div>

    @if ($create)
        <div class="form-group {{ $errors->has('padre') ? 'has-error':'' }}">
            <label class="col-xs-4 control-label">Menu item padre</label>
            <div class="input-group col-xs-6">
                {!! Form::select('padre', ['' => 'Seleccione menu item padre'] + $menuItems->toArray(), old('padre', $menuItem->getPadre()), ['class' => 'form-control']) !!}
                @if($errors->has('padre'))
                    {!! $errors->first('padre', '<small class="control-label">:message</small>') !!}
                @endif
            </div>
        </div>
    @else
        @if ($menuItem->getPadre())
            <div class="form-group">
                <label class="col-xs-4 control-label">Menú item padre</label>
                <div class="input-group col-xs-6">
                    <input type="text" class="form-control readonly" readonly="readonly" value="{{ $menuItem->getPadre()->getNombre() }}"/>
                </div>
            </div>
        @endif
    @endif

    <div class="form-group {{ $errors->has('nombre') ? 'has-error':'' }}">
        <label class="col-xs-4 control-label">Nombre</label>
        <div class="input-group col-xs-6">
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $menuItem->getNombre()) }}"/>
            @if($errors->has('nombre'))
            {!! $errors->first('nombre', '<small class="control-label">:message</small>') !!}
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('orden') ? 'has-error':'' }}">
        <label class="col-xs-4 control-label">Orden</label>
        <div class="input-group col-xs-6">
            <input type="number" name="orden" id="orden" class="form-control" value="{{ old('orden', $menuItem->getOrden()) }}" />
            @if($errors->has('orden'))
            {!! $errors->first('orden', '<small class="control-label">:message</small>') !!}
            @endif
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-4  control-label" for="visible"></label>
        <div class="input-group ol-xs-6">
            {{ Form::checkbox('visible', 1, old('visible', $menuItem->getVisible()), ['id' => 'visible']) }}
            &nbsp;&nbsp;<label for="visible">Visible</label>
        </div>
    </div>

    <div class="form-group hidden">
        <label class="col-xs-4  control-label" for="externo"></label>
        <div class="input-group ol-xs-6">
            {{ Form::checkbox('externo', 1, 1, ['id' => 'externo']) }}
            &nbsp;&nbsp;<label for="externo">Externo</label>
        </div>
    </div>

    @if (!$menuItem->getExterno())
        <div class="form-group {{ $errors->has('accion') ? 'has-error':'' }}">
            <label class="col-xs-4 control-label">Acción</label>
            <div class="input-group col-xs-6">
                <textarea name="accion" id="accion" class="form-control" >{{ old('accion', $menuItem->getAccion()) }}</textarea>
                @if($errors->has('accion'))
                    {!! $errors->first('accion', '<small class="control-label">:message</small>') !!}
                @endif
            </div>
        </div>
    @else
        <div class="form-group {{ $errors->has('enlace') ? 'has-error':'' }}">
            <label class="col-xs-4 control-label">Enlace</label>
            <div class="input-group col-xs-6">
                <input type="text" name="enlace" id="enlace" class="form-control" value="{{ old('enlace', $menuItem->getEnlaceExterno()) }}"/>
                @if($errors->has('enlace'))
                    {!! $errors->first('enlace', '<small class="control-label">:message</small>') !!}
                @endif
            </div>
        </div>
    @endif

    @if ($menuItem->getTieneHijos())
        <div class="form-group">
            <label class="col-xs-4 control-label">Menú items hijos</label>
            <div class="input-group col-xs-6">
                <ul>
                    @foreach($menuItem->getHijos() as $menuItemHijo)
                        <li>
                            {{ $menuItemHijo->getNombre()  }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <hr />

    <div class="form-group">
        <label class="col-xs-4 control-label"></label>
        <div class="col-xs-6 col-xs-offset-4">
            <button type="submit" class="btn btn-primary">
                Aceptar
            </button>
            <a href="{{ route('simplecms.menus.menu_items.index', $menu->getId()) }}" class="btn btn-default">Cancelar</a>
        </div>
    </div>

</fieldset>

@section('footer-scripts')
@parent

@endsection