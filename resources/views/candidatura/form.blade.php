
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('token_user') }}</label>
    <div>
        {{ Form::text('token_user', $candidatura->token_user, ['class' => 'form-control' .
        ($errors->has('token_user') ? ' is-invalid' : ''), 'placeholder' => 'Token User']) }}
        {!! $errors->first('token_user', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Token del usuario.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('empresa_id') }}</label>
    <div>
        {{ Form::text('empresa_id', $candidatura->empresa_id, ['class' => 'form-control' .
        ($errors->has('empresa_id') ? ' is-invalid' : ''), 'placeholder' => 'Empresa Id']) }}
        {!! $errors->first('empresa_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">ID de la empresa.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('vacante_id') }}</label>
    <div>
        {{ Form::text('vacante_id', $candidatura->vacante_id, ['class' => 'form-control' .
        ($errors->has('vacante_id') ? ' is-invalid' : ''), 'placeholder' => 'Vacante Id']) }}
        {!! $errors->first('vacante_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">ID de la vacante.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('estado') }}</label>
    <div>
        {{ Form::text('estado', $candidatura->estado, ['class' => 'form-control' .
        ($errors->has('estado') ? ' is-invalid' : ''), 'placeholder' => 'Estado']) }}
        {!! $errors->first('estado', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">candidatura <b>estado</b> instruction.</small>
    </div>
</div>

    <div class="form-footer">
        <div class="text-end">
            <div class="d-flex">
                <a href="#" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary ms-auto ajax-submit">Subir</button>
            </div>
        </div>
    </div>
