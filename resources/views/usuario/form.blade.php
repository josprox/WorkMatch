<div class="form-group mb-3">
    <label class="form-label"> {{ Form::label('token_user') }}</label>
    <div>
        {{ Form::text('token_user', $usuario->token_user, ['class' => 'form-control' .
        ($errors->has('token_user') ? ' is-invalid' : ''), 'placeholder' => 'Token User']) }}
        {!! $errors->first('token_user', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Pon el <b>Token del usuario</b> aquí.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label"> {{ Form::label('especialidades') }}</label>
    <div>
        {{ Form::text('especialidades', $usuario->especialidades, ['class' => 'form-control' .
        ($errors->has('especialidades') ? ' is-invalid' : ''), 'placeholder' => 'Especialidades']) }}
        {!! $errors->first('especialidades', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Pon las <b>especialidades del usuario</b> aquí.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label for="curriculum" class="form-label">Curriculum</label>
    <div>
        <textarea name="curriculum" id="curriculum"
            class="form-control {{ $errors->has('curriculum') ? 'is-invalid' : '' }}"
            placeholder="Curriculum">{{ old('curriculum', $usuario->curriculum) }}</textarea>

        {!! $errors->first('curriculum', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Pon el <b>curriculum</b> aquí.</small>
    </div>
</div>



<div class="form-footer">
    <div class="text-end">
        <div class="d-flex">
            <a href="/usuarios" class="btn btn-danger">Cancelar</a>
            <button type="submit" class="btn btn-primary ms-auto ajax-submit">Enviar</button>
        </div>
    </div>
</div>