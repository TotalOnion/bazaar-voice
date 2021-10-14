<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://developer.p-r.io
 * @since      1.0.0
 *
 * @package    Pr_Bazaarvoice
 * @subpackage Pr_Bazaarvoice/admin/partials
 */
?>

<div class="wrap">
    <h1>
        <?php _e( 'Bazaarvoice Settings', 'PR Bazaarvoice' ); ?>
    </h1>
    <form method="post" action="options.php">
        <?php
            settings_fields(PR_BAZAARVOICE_NAME);
            do_settings_sections(PR_BAZAARVOICE_SLUG . '-settings-page' );
            submit_button();
        ?>
    </form>
</div>

<!-- <div class="wrap">
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <form method="post" name="cleanup_options" action="options.php" enctype="multipart/form-data">
        <?php
        function getValue($options, $name){
            return (!empty($options[$name]) ? $options[$name] : '');
        }
        
        // Grab all options
        $options = get_option(PR_BAZAARVOICE_NAME);
        
        // Cleanup
        $bazaarvoice_custom_code = getValue($options, PR_BAZAARVOICE_NAME . '-default-code');
        $bazaarvoice_custom_url_single_wpml_support_message = "Some intructions here if needed";
        
        // Settings
        settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);
        ?>
        <h3>Default Settings</h3>

        <fieldset class="default_code">
            <legend class="screen-reader-text"><span>Bazaarvoice default code</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-pr-bazaarvoice-default-code">
                <span style="display:inline-block;width: 200px;vertical-align: top;"><strong>Bazaarvoice default code</strong></span>
                <textarea rows="10" cols="100" class="bazaarvoice-textarea" id="<?php echo $this->plugin_name; ?>-<?php echo PR_BAZAARVOICE_NAME; ?>-default-code" name="<?php echo PR_BAZAARVOICE_NAME; ?>[<?php echo PR_BAZAARVOICE_NAME; ?>-default-code]"><?php if(!empty($bazaarvoice_custom_code)) { echo $bazaarvoice_custom_code; } ?></textarea>
            </label>
            
            
        </fieldset>

        <h3>Market Overrides</h3>



        <?php submit_button('Save configuration', 'primary','submit', TRUE); ?>

    </form>
</div>
<style>

form fieldset{
        margin:5px 0;
    }

</style>
<script>

    tippy('[data-tippy-content]', {
        interactive: true,
        allowHTML: true,
        theme: 'light-border'
    });

</script> -->