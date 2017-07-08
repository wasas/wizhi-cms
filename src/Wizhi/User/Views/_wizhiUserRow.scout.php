<tr class="wizhi-field-container wizhi-user-field-container">
    <th class="wizhi-label wizhi-user-label" scope="row">
        {!! Wizhi\Facades\Form::label($field['features']['title'], ['for' => $field['atts']['id']]) !!}
    </th>
    <td>
        {!! $field->user() !!}
    </td>
</tr>