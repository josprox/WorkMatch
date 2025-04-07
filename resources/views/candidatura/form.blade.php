<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('token_user') }}</label>
    <div>
        {{ Form::text('token_user', $candidatura->token_user, ['class' => 'form-control' . 
        ($errors->has('token_user') ? ' is-invalid' : ''), 'placeholder' => 'Token User']) }}
        {!! $errors->first('token_user', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Token del usuario.</small>
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('empresa_id') }}</label>
    <div>
        <select name="empresa_id" id="empresa_id" class="form-control {{ $errors->has('empresa_id') ? 'is-invalid' : '' }}">
            <option value="">Selecciona una empresa</option>
            @foreach($empresas as $empresa)
                <option value="{{ $empresa->id }}" {{ old('empresa_id', $candidatura->empresa_id) == $empresa->id ? 'selected' : '' }}>
                    {{ $empresa->nombre }}
                </option>
            @endforeach
        </select>
        {!! $errors->first('empresa_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Selecciona la empresa.</small>
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('vacante_id') }}</label>
    <div>
        <select name="vacante_id" id="vacante_id" class="form-control {{ $errors->has('vacante_id') ? 'is-invalid' : '' }}">
            <option value="">Selecciona una vacante</option>
        </select>
        {!! $errors->first('vacante_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Selecciona la vacante correspondiente.</small>
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('estado') }}</label>
    <div>
        <select name="estado" id="estado" class="form-control {{ $errors->has('estado') ? 'is-invalid' : '' }}">
            <option value="">Selecciona el estado</option>
            <option value="Aprobado" {{ old('estado', $candidatura->estado) == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
            <option value="Pendiente" {{ old('estado', $candidatura->estado) == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="En curso" {{ old('estado', $candidatura->estado) == 'En curso' ? 'selected' : '' }}>En curso</option>
            <option value="Rechazado" {{ old('estado', $candidatura->estado) == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
            <option value="Otro" {{ old('estado', $candidatura->estado) == 'Otro' ? 'selected' : '' }}>Otro</option>
        </select>
        {!! $errors->first('estado', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Estado de la candidatura.</small>
    </div>
</div>


<div class="form-footer">
    <div class="text-end">
        <div class="d-flex">
            <a href="/candidaturas" class="btn btn-danger">Cancelar</a>
            <button type="submit" class="btn btn-primary ms-auto ajax-submit">Subir</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Verifica que jQuery esté cargado
    if (typeof jQuery == 'undefined') {
        console.error('jQuery no está cargado');
        return;
    }
    
    console.log('Script cargado correctamente'); // Para depuración
    
    $(document).on('change', '#empresa_id', function() {
        var empresaId = $(this).val();
        var vacanteSelect = $('#vacante_id');
        
        console.log('Empresa seleccionada:', empresaId); // Para depuración
        
        // Resetear el select
        vacanteSelect.empty().append('<option value="">Cargando vacantes...</option>');
        
        if (!empresaId) {
            vacanteSelect.empty().append('<option value="">Seleccione una empresa</option>');
            return;
        }
        
        $.ajax({
            url: '/vacantes/por-empresa/' + empresaId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta recibida:', response); // Para depuración
                
                vacanteSelect.empty();
                
                if (response.success && response.vacantes && response.vacantes.length > 0) {
                    vacanteSelect.append('<option value="">Seleccione una vacante</option>');
                    $.each(response.vacantes, function(index, vacante) {
                        vacanteSelect.append(
                            $('<option>', {
                                value: vacante.id,
                                text: vacante.titulo
                            })
                        );
                    });
                } else {
                    vacanteSelect.append('<option value="" disabled>No hay vacantes disponibles</option>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la petición:', status, error);
                vacanteSelect.empty().append('<option value="">Error al cargar vacantes</option>');
            }
        });
    });
});
</script>
