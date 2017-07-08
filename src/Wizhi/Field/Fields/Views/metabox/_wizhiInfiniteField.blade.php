<div class="wizhi-infinite-container">
    <table class="wizhi-infinite">
        <tbody class="wizhi-infinite-sortable" data-limit="{{ $field['features']['limit'] }}">

        <?php
        // Rows
        for($i = 1; $i <= $field->getRows(); $i++):

            if(0 < $field['features']['limit'] && $i > $field['features']['limit']) break;
            ?>

            <tr class="wizhi-infinite-row">
                <td class="wizhi-infinite-order"><span>{{ $i }}</span></td>
                <td class="wizhi-infinite-inner">
                    <table>
                        <tbody>
                        <?php
                        foreach($field['fields'] as $f):
                            // Set the id attribute.
                            $f_atts = $f['atts']; // Grab ALL attributes of the field.
                            $defaultId = $f_atts['id']; // Keep a copy of the field id attribute.
                            $f_atts['id'] = $field['name'].'-'.$i.'-'.$f['name'].'-id'; // Update the id attribute of the field.
                            $f['atts'] = $f_atts; // Update ALL attributes of the field. Contains its new id value.

                            // Grab the value if it exists.
                            if(isset($field['value'][$i][$f['name']])){
                                $f['value'] = $field['value'][$i][$f['name']];
                            }

                            // Set the name attribute.
                            // Note: this completely change the name attribute. Do not write
                            // any code that would need the default 'name' attribute below.
                            $defaultName = $f['name'];
                            $f['name'] = $field['name'].'['.$i.']['.$f['name'].']';

                            // Render the field.
                            echo(Wizhi\Facades\View::make('_wizhiMetaboxRow', ['field' => $f])->render());

                            // Reset Id, name and value.
                            $f_atts['id'] = $defaultId; // Reset field id attribute to its original value.
                            $f['atts'] = $f_atts; // Update ALL attributes of the field. Contains its original id value.
                            $f['name'] = $defaultName; // Reset name value with its original name.
                            unset($f['value']);
                        endforeach;
                        ?>
                        </tbody>
                    </table>
                </td>
                <td class="wizhi-infinite-options">
                    <span class="wizhi-infinite-add"></span>
                    <span class="wizhi-infinite-remove"></span>
                </td>
            </tr>

        <?php
        endfor;
        // End rows.
        ?>

        </tbody>
    </table>
    @if(isset($field['features']['info']))
        <div class="wizhi-field-info">
            <p class="description">{!! $field['features']['info'] !!}</p>
        </div>
    @endif
    <div class="wizhi-infinite-add-field-container">
        <button type="button" id="wizhi-infinite-main-add" class="button-primary"><?php _e('Add'); ?></button>
    </div>
</div>