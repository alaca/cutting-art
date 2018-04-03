<?php defined('ABSPATH') or die('No direct script access!'); ?>

<div class="wrap">

    <?php require CTA_ADMIN_DIR . 'tabs.php'; ?>

    <div class="p18a-page-wrapper">

        <h2><?php _e('Add conversion type', 'cta'); ?></h2>

        <form method="post" action="<?php echo admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=conversion-types'); ?>">
            <?php wp_nonce_field('insert_conversion_type', 'cta'); ?>

            <table id="cta_add_param_table">
                <tr>
                    <td>
                        <?php _e('Conversion type name', 'cta'); ?>
                    </td>
                    <td>
                        <input type="text" name="conversion_type_name" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php _e('Conversion type code', 'cta'); ?>
                    </td>
                    <td>
                        <input type="text" name="conversion_type_code" />
                    </td>
                </tr>

            </table>

            <br>

            <input type="submit" name="insert_conversion_type" value="<?php _e('Save'); ?>" class="button button-primary">

        </form>



    </div>

</div>