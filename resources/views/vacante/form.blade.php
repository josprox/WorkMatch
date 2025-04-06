<div class="form-group mb-3">
    <label class="form-label"> {{ Form::label('titulo') }}</label>
    <div>
        {{ Form::text('titulo', $vacante->titulo, ['class' => 'form-control' .
        ($errors->has('titulo') ? ' is-invalid' : ''), 'placeholder' => 'Titulo']) }}
        {!! $errors->first('titulo', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Título de la vacante.</small>
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('descripcion', 'Descripción') }}</label>
    <div>
        {{ Form::textarea('descripcion', $vacante->descripcion, [
                'id' => 'md',
                'class' => 'form-control' . ($errors->has('descripcion') ? ' is-invalid' : ''),
                'placeholder' => 'Descripción en formato Markdown'
            ]) }}
        {!! $errors->first('descripcion', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Descripción de la vacante (puedes usar Markdown).</small>
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label"> {{ Form::label('sueldo') }}</label>
    <div>
        {{ Form::text('sueldo', $vacante->sueldo, ['class' => 'form-control' .
        ($errors->has('sueldo') ? ' is-invalid' : ''), 'placeholder' => 'Sueldo']) }}
        {!! $errors->first('sueldo', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Sueldo de la vacante.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label"> {{ Form::label('modalidad') }}</label>
    <div>
        {{ Form::text('modalidad', $vacante->modalidad, ['class' => 'form-control' .
        ($errors->has('modalidad') ? ' is-invalid' : ''), 'placeholder' => 'Modalidad']) }}
        {!! $errors->first('modalidad', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Modalidad de la vacante.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('empresa_id', 'Empresa') }}</label>
    <div>
        {{ Form::select('empresa_id', $empresas->pluck('nombre', 'id'), $vacante->empresa_id, ['class' => 'form-control' . ($errors->has('empresa_id') ? ' is-invalid' : ''), 'placeholder' => 'Seleccione una empresa']) }}
        {!! $errors->first('empresa_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Seleccione la empresa.</small>
    </div>
</div>

<div class="form-footer">
    <div class="text-end">
        <div class="d-flex">
            <a href="/vacantes" class="btn btn-danger">Cancelar</a>
            <button type="submit" class="btn btn-primary ms-auto ajax-submit">Subir</button>
        </div>
    </div>
</div>