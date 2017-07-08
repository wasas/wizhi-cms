@foreach($fields as $field)
    <div class="form-field {{ 'wizhi-term-'.$field['name'].'-wrap' }}">
        {!! Wizhi\Facades\Form::label($field['features']['title'], ['for' => $field['atts']['id']]) !!}
        {!! $field->taxonomy() !!}
    </div>
@endforeach