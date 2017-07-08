{!! Wizhi\Facades\Form::number($field['name'], $field['value'], $field['atts']) !!}

@if(isset($field['features']['info']))
    <div class="wizhi-field-info">
        <p class="description">{!! $field['features']['info'] !!}</p>
    </div>
@endif