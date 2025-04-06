
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('nombre') }}</label>
    <div>
        {{ Form::text('nombre', $empresa->nombre, ['class' => 'form-control' .
        ($errors->has('nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre']) }}
        {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Ingresa el <b>nombre</b> de la empresa.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('correo') }}</label>
    <div>
        {{ Form::text('correo', $empresa->correo, ['class' => 'form-control' .
        ($errors->has('correo') ? ' is-invalid' : ''), 'placeholder' => 'Correo']) }}
        {!! $errors->first('correo', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Ingresa el <b>correo</b> de la empresa.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('contra') }}</label>
    <div>
        {{ Form::text('contra', $empresa->contra, ['class' => 'form-control' .
        ($errors->has('contra') ? ' is-invalid' : ''), 'placeholder' => 'Contra']) }}
        {!! $errors->first('contra', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Ingresa la <b>contraseña</b> de acceso para la empresa.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('ubicacion') }}</label>
    <div>
        {{ Form::text('ubicacion', $empresa->ubicacion, ['class' => 'form-control' .
        ($errors->has('ubicacion') ? ' is-invalid' : ''), 'placeholder' => 'Ubicacion']) }}
        {!! $errors->first('ubicacion', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Ingresa la <b>ubicación</b> de la empresa.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('telefono') }}</label>
    <div>
        {{ Form::text('telefono', $empresa->telefono, ['class' => 'form-control' .
        ($errors->has('telefono') ? ' is-invalid' : ''), 'placeholder' => 'Telefono']) }}
        {!! $errors->first('telefono', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Ingresa el <b>telefono</b> de la empresa.</small>
    </div>
</div>

    <div class="form-footer">
        <div class="text-end">
        <div class="d-flex">
                <a href="/empresas" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary ms-auto ajax-submit">Enviar</button>
            </div>
        </div>
    </div>
