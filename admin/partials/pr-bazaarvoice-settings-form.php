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
        <?php _e( 'BazaarVoice Settings', 'PR Bazaarvoice' ); ?>
    </h1>
    <form method="post" action="options.php">
        <?php
            settings_fields(PR_BAZAARVOICE_NAME);
            do_settings_sections(PR_BAZAARVOICE_SLUG . '-settings-page' );
            submit_button();
        ?>
    </form>
</div>
