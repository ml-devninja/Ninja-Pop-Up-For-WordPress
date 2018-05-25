<?php

/**
 * @param DateTime $date Date that is to be checked if it falls between $startDate and $endDate
 * @param DateTime $startDate Date should be after this date to return true
 * @param DateTime $endDate Date should be before this date to return true
 * return bool
 */
    function isDateBetweenDates($options) {
    if($options['date_start'] && $options['date_stop'])
        $date = new DateTime();
        $startDate = new DateTime($options['date_start']);
        $endDate = new DateTime($options['date_stop']);
        return $options['data_range'] && $date > $startDate && $date < $endDate;
}






function print_npu_frontend($options){

    if(isDateBetweenDates($options) || !$options['data_range']) : ?>
        <?php if(is_front_page() || is_home() || !$options['only_home']) : ?>
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
                        <?php echo wpautop($options['pop_up_content']); ?>

                    </div>

                <?php if($options['show_buttons']) : ?>
                    <div class="action-holder">
                        <button class="action-ok"><?php echo $options['position'] === 'center' ? "ZAMKNIJ" : 'AKCEPTUJĘ'; ?></button>
                        <?php if($options['position'] !== 'center') : ?><button class="action-cancel">NIE TERAZ</button><?php endif; ?>
                    </div>

                <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php endif; ?>

<?php } ?>