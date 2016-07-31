<div class='wrap'>
    <h1>
        编辑数据
        <a class="page-title-action" href='<?php echo $this->url['list'] ?>'>返回列表</a>
    </h1>

    <?php
        if ($status == "error") {
            echo "<div class='error'><p>$message</p></div>";
        } else if ($status == "success") {
            echo "<div class='updated'><p>$message</p></div>";
        }
    ?>

    <?php if (!empty($row)) { ?>
        <form method='post' action='<?php echo $this->url['edit'] ?>'>
        <table class='wp-list-table form-table fixed'>
            <?php
                require_once("util.php");
                foreach ($row as $name => $value) {
                    if ($name == $primary_key) {
                        echo "<tr><th class='row'>" . $column_names[ $name ] . " *</th><td><input type='text' readonly='readonly' name='$name' value='$value'/></td></tr>";
                    } else {
                        if( !in_array( $name, $excluded_columns ) ){
                            echo "<tr><th class='row'>" . $column_names[ $name ] . "</th><td>" . data_type2html_input($columns[$name], $name, $value) . "</td></tr>";
                        }
                    }
                }
            ?>
        </table>
        <div class="tablenav bottom">
        <input type='submit' name='update' value='更新' class='button'>&nbsp;
        <input type='submit' name='delete' value='删除' class='button' onclick="return confirm('确定要删除吗? ?')">
        </div>
        <input type="hidden" name="id" value="<?php echo $id ?>">
        </form>
    <?php } ?>
</div>