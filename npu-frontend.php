<?php function print_npu_frontend($options){ ?>

<div id="npu-pop-up" class=" h-align-centered <?php if($options['position'] === 'center') : ?>v-align-centered <?php else : ?>sticky_bottom<?php endif; ?>" >
    <div class="wrapper display-flex <?php echo $options['position']; ?>">
        <?php if($options['show_image']) : ?>
        <div class="image-col <?php if($options['position'] === 'center') : ?>display-flex v-align-centered h-align-centered <?php endif; ?>">
            <img src="<?php echo $options['pop_up_image_path']; ?>" alt="">
        </div>
        <?php endif; ?>
        <div class="content-col ">
            <?php if($options['pop_up_title']) : ?><h2><?php echo $options['pop_up_title']; ?></h2><?php  endif; ?>
            <div class="content <?php if($options['show_scroll_bar']) : ?>display__scroll<?php endif; ?>">
                <?php echo $options['pop_up_content']; ?>

            </div>

        <?php if($options['show_buttons']) : ?>
<div class="action-holder">
            <button class="action-ok">AKCEPTUJE</button>
            <button class="action-cancel">NIE TERAZ</button>
</div>

        <?php endif; ?>
        </div>
    </div>
</div>

<?php } ?>