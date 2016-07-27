<div class='wrap'>
    <h1>
        添加数据
        <a class="page-title-action" href='<?php echo $this->url['list'] ?>'>返回列表</a>
    </h1>
    <form method='post' action='<?php echo $this->url['edit'] ?>'>
        <table class='wp-list-table form-table fixed'>
            <?php
                require_once("util.php");
                foreach ($columns as $name => $type) {
                    if ($name == $primary_key) {
                        echo "<tr><th class='row'>" . $column_names[ $name ] . " *</th><td>" . data_type2html_input($columns[$name], $name, $new_id) . "</td></tr>";
                    } else {
                        echo "<tr><th class='row'>" . $column_names[ $name ] . "</th><td>" . data_type2html_input($columns[$name], $name, $value) . "</td></tr>";
                    }
                }
            ?>
        </table>
        <div class="tablenav bottom">
        <input type='submit' name='add' value='添加' class='button'>
        </div>
    </form>
</div>