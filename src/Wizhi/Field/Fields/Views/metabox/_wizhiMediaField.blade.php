{!! Wizhi\Facades\Form::hidden($field['name'], $field['value'], $field['atts']) !!}

<table class="wizhi-media">
    <tr>
        <td class="wizhi-media-preview <?php if(empty($field['value'])){ echo('wizhi-media--hidden'); } ?>">
            <div class="wizhi-media-preview-inner">
                <?php
                    $isFile = false;
                    $src = '';

                    if (!empty($field['value']) && is_numeric($field['value']))
                    {
                        if (wp_attachment_is_image($field['value']))
                        {
                            $src = wp_get_attachment_image_src($field['value'], '_wizhi_media');
                            $src = $src[0];
                        }
                        else
                        {
                            $src = wp_get_attachment_image_src($field['value'], '_wizhi_media', true);
                            $src = $src[0];
                            $isFile = true;
                        }
                    }
                ?>
                <div class="centered">
                    <img class="wizhi-media-thumbnail <?php if ($isFile){ echo('icon'); } ?>" alt="Media Thumbnail" src="{{ $src }}"/>
                </div>
                <div class="filename <?php if ($isFile){ echo('show'); } ?>">
                    <div><?php if(!empty($field['value']) && is_numeric($field['value'])){ echo(get_the_title($field['value'])); } ?></div>
                </div>
            </div>
        </td>
        <td class="wizhi-media-details">
            <div class="wizhi-media-inner">
                <div class="wizhi-media-infos <?php if(empty($field['value'])){ echo('wizhi-media--hidden'); } ?>">
                    <h4><?php _e('Attachment ID'); ?>:</h4>
                    <p class="wizhi-media__path">{{ $field['value'] }}</p>
                </div>
                <div class="wizhi-media__buttons">
                    <button id="wizhi-media-add" type="button" class="button button-primary <?php if(!empty($field['value'])){ echo('wizhi-media--hidden'); } ?>"><?php _e('Add'); ?></button>
                    <button id="wizhi-media-delete" type="button" class="button wizhi-button-remove <?php if(empty($field['value'])){ echo('wizhi-media--hidden'); } ?>"><?php  _e('Delete'); ?></button>
                </div>
            </div>
        </td>
    </tr>
</table>

@if(isset($field['features']['info']))
    <div class="wizhi-field-info">
        <p class="description">{!! $field['features']['info'] !!}</p>
    </div>
@endif