<tr class="wizhi-field-container">
    <th class="wizhi-label" scope="row">
        {!! Wizhi\Facades\Form::label($field['features']['title'], ['for' => $field['atts']['id']]) !!}
    </th>
    <td class="wizhi-field">
        {!! $field->metabox() !!}
    </td>
</tr>