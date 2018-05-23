<?php
/*
Plugin Name: Ninja Pop Up for WordPress
Description: Add a pop-up easily! Add time slot, custom text or image! 
Version: 0.1
*/

class NINJA_POP_UP
{
    function __construct() {

        // Adding Plugin Menu
        add_action( 'admin_menu', array( &$this, 'npu_menu' ) );

        // Load custom assets on admin page.
        add_action( 'admin_enqueue_scripts', array( &$this, 'npu_assets' ) );

        // Load custom assets on front-end layer
        add_action( 'wp_head', array( &$this, 'enqueue_libs' ) );

        // Register Settings
        add_action( 'admin_init', array( &$this, 'npu_settings' ) );

        // Add Two Menus - Mobile & Tablet
        // add_action( 'init', array( &$this, 'register_material_menus' ));

        //  Print front-end layer
        add_action( 'wp_head', array( &$this, 'print_on_frontend' ) );

    } // end constructor


    /*--------------------------------------------*
     * Admin Menu
     *--------------------------------------------*/

    function npu_menu()
    {
        $page_title = __('Ninja Pop-Up', 'npu');
        $menu_title = __('Ninja Pop-Up', 'npu');
        $capability = 'manage_options';
        $menu_slug = 'npu-options';
        $function = array(&$this, 'npu_menu_contents');
        add_options_page($page_title, $menu_title, $capability, $menu_slug, $function);

    }

    /*--------------------------------------------*
     * Load Necessary JavaScript Files
     *--------------------------------------------*/

    function npu_assets() {
        $debug = false;

        if (isset($_GET['page']) && $_GET['page'] == 'npu-options') {

            wp_enqueue_style( 'thickbox' ); // Stylesheet used by Thickbox
            wp_enqueue_script( 'thickbox' );
            wp_enqueue_script( 'media-upload' );
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_media();
            wp_register_script('npu_admin', plugins_url( '/assets/npu_admin.js' , __FILE__ ), array( 'thickbox', 'media-upload', 'wp-color-picker' ));
            wp_enqueue_script('npu_admin');


            if($_GET['settings-updated'] === 'true' && $debug == false){
                $this->npu_recompile_less();
            }
        }

    }


    function enqueue_libs(){
        // $options = get_option( 'npu_settings' );
        // add_filter('show_admin_bar', '__return_false');

//        wp_enqueue_style( 'icofont-base', plugin_dir_url(__FILE__).'style.css' );
        wp_enqueue_style( 'npu-base', plugin_dir_url(__FILE__).'assets/styles.css' );
//        wp_enqueue_script( 'jQuery', 'http://code.jquery.com/jquery-2.1.4.min.js', array(), '1.0.0', true );
//        wp_enqueue_script( 'velocity', '//cdn.jsdelivr.net/velocity/1.2.3/velocity.min.js', array(), '1.0.0', true );
        // if($options['gestures']){
        //     wp_enqueue_script( 'jQuery-mobile', '//code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js', array(), '1.0.0', true );
        // }
        wp_enqueue_script( 'npu-base', plugin_dir_url(__FILE__).'assets/npu.js', array(), '1.0.0', true );
    }


    /*--------------------------------------------*
     * LESS compiler
     *--------------------------------------------*/

    function npu_recompile_less() {

            $options = get_option( 'npu_settings' );
            $color = $options["main_color"];
            $path = plugin_dir_path( __FILE__ );
            require "assets/lessc.inc.php";
            $less = new lessc;
            $less->setVariables(array(
                "main_color" => $color
            ));
            $css =  $less->compileFile($path."assets/styles.less");
            file_put_contents($path."assets/styles.css", $css);
    }


    /*--------------------------------------------*
     * Print radio-buttons based on prepared data (array)
     *--------------------------------------------*/

    function radioButtons($data){
        $options = get_option( 'npu_settings' );
        $i=1; foreach($data['options'] as $option) : ?>

            <label for="<?php echo $data['id'].$i; ?>"><input type="radio" id="<?php echo $data['id'].$i; ?>" name="npu_settings[<?php echo $data['setting']; ?>]" value="<?php echo $option[0]; ?>" <?php checked($option[0], $options[$data['setting']]); ?>/><?php echo $option[1]; ?></label>

        <?php $i++; endforeach; }


    /*--------------------------------------------*
    * Print checkboxes based on prepared data (array)
    *--------------------------------------------*/

    function checkboxes($data){
        $options = get_option('npu_settings');
        $i=1; foreach($data['options'] as $option) : ?>

            <label for="<?php echo $data['id'].$i; ?>">
                <input type="checkbox" id="<?php echo $data['id'].$i; ?>"  name="npu_settings[<?php echo $data['setting'].$option[0]; ?>]" value="<?php echo true; ?>" <?php checked(true, $options[$data['setting'].$option[0]]); ?>/>
                <?php echo $option[1]; ?>
            </label><br/>

            <?php $i++; endforeach; }

    /*--------------------------------------------*
     * Settings & Settings Page
     *--------------------------------------------*/

    public function npu_menu_contents()
    {
        ?>
        <h2><?php _e('Ninja Pop Up', 'npu'); ?></h2>
        <div class="wrap">


            <form method="post" action="options.php">
            <pre>

                </pre>
                <?php //wp_nonce_field('update-options'); ?>
                <?php settings_fields('npu_settings'); ?>
                <?php do_settings_sections('npu_settings'); ?>
                


                <p class="submit">
                    <input name="Submit" type="submit" class="button-primary"
                           value="<?php _e('Zapisz zmiany', 'npu'); ?>"/>
                </p>
                <?php
                $options = get_option( 'npu_settings' );
                var_dump($options);
                ?>

            </form>
        </div>

    <?php
    }

    function npu_settings()
    {
        register_setting('npu_settings', 'npu_settings');

        add_settings_section('displayed', 'Wybierz elementy do wyświetlenia', null, 'npu_settings');
            add_settings_field('is_displayed', 'Pojawi się: ', array( &$this, 'will_display' ), 'npu_settings', 'displayed');
        
        add_settings_section('other', 'Treść', null, 'npu_settings');
            add_settings_field('pop_up_title', null, array( &$this, 'pop_up_title' ), 'npu_settings', 'other');
            add_settings_field('pop_up_content', null, array( &$this, 'pop_up_content' ), 'npu_settings', 'other');
            add_settings_field('main_color', 'Podstawowy kolor', array( &$this, 'main_color' ), 'npu_settings', 'other');
            add_settings_field('pop_up_image', 'Obrazek', array( &$this, 'pop_up_image' ), 'npu_settings', 'other');
            add_settings_field('pop_up_position', 'Pozycja: ', array( &$this, 'pop_up_position' ), 'npu_settings', 'other');
    }


    function pop_up_title(){
        $options = get_option( 'npu_settings' );
        ?>
        <span class="text">
           <label for="npu_settings[pop_up_title]">Tytuł<br/><input type='text' id='npu_settings[pop_up_title]' class='regular-text' name='npu_settings[pop_up_title]' value='<?php echo $options["pop_up_title"]; ?>'/></label>
       </span>
    <?php
    }

    function will_display() {
        $items = array(
            'id' => 'header_menu',
            'setting' => 'show_',
            'options' => array(
                ['title', 'Tytuł'],
                ['image', 'Obrazek'],
                ['scroll_bar', 'Scroll-Bar przy tekscie'],
                ['buttons', 'Wyświetl przyciski']
            )
        );
        ?>
        <span class='checkboxes'>
            <?php $this->checkboxes($items); ?>
        </span>
    <?php
    }

    function pop_up_position() {
        $items = array(
            'id' => 'popup_position',
            'setting' => 'position',
            'options' => array(
                ['center', 'Wycentrowany'],
                ['sticky_bottom', 'Przyklejony do dołu'],
                // ['scroll_bar', 'Scroll-Bar przy tekscie']
            )
        );
        ?>
        <span class='checkboxes'>
            <?php $this->radioButtons($items); ?>
        </span>
    <?php
    }

    function pop_up_image(){
        $options = get_option( 'npu_settings' ); ?>
<span class='upload'>
            <img src='<?php echo esc_url( $options["pop_up_image_path"] ); ?>' class='preview-upload' style="max-width: 300px;"/><br/>
            <label for="npu_settings[pop_up_image_path]">
                <input type='hidden' id='npu_settings[pop_up_image_path]' class='regular-text text-upload' name='npu_settings[pop_up_image_path]' value='<?php echo esc_url( $options["pop_up_image_path"] ); ?>' />
                <input type='button' class='button button-upload' value='Wybierz logo'/>
            </label>
        </span>
        <?php
    }

    function main_color(){
        $options = get_option( 'npu_settings' ); ?>

        <span class='text box'>
            <label for="npu_settings[main_color]">
                <input type='text' id='npu_settings[main_color]' class='color-field' name='npu_settings[main_color]' value='<?php echo $options["main_color"]; ?>'/>
            </label>
        </span>

    <?php }

    function pop_up_content(){
        $options = get_option( 'npu_settings' );
        $content = $options['pop_up_content'];
        $args = array('textarea_name' => 'npu_settings[pop_up_content]');
        wp_editor($content, 'pop_up_content', $args);
    }

    function print_on_frontend(){
        $options = get_option('npu_settings');
        include_once 'npu-frontend.php';
        print_npu_frontend($options);
    }

}
$npu = new NINJA_POP_UP(__FILE__);