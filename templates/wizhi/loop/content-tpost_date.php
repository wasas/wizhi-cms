<?php
/**
 * Loop Template Name: 日期-标题列表
 *
 */
?>

<div class="spost tpost tpost3 clearfix">
    <div class="entry-image">
        <h2><?php the_time('d'); ?></h2>
        <p><?php the_time('Y-m'); ?></p>
    </div>
    <div class="entry-c">
        <ul class="clearfix entry-meta">
            <li>[ 活动日期：<?php the_time('Y-m-d'); ?> ]</li>
        </ul>
        <div class="entry-title">
            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
        </div>
    </div>
</div>