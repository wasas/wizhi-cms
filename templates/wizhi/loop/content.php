<?php
/**
 * Loop Template Name: 默认循环模板
 *
 */
?>

<div class="spost tpost clearfix">
    <div class="entry-c">
        <div class="entry-title">
            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
        </div>
        <ul class="clearfix entry-meta">
            <li><?php the_time('Y-m-d'); ?></li>
        </ul>
    </div>
</div>