<tr class="form-field {{ 'wizhi-term-'.$field['name'].'-wrap' }}">
    <th scope="row">
        {!! Wizhi\Facades\Form::label($field['features']['title'], ['for' => $field['atts']['id']]) !!}
    </th>
    <td>
        {!! $field->taxonomy() !!}
    </td>
</tr>