<?php defined('ABSPATH') or die('No direct script access!'); ?>

<div class="wrap">

    <?php require CTA_ADMIN_DIR . 'tabs.php'; ?>

    <div class="p18a-page-wrapper">

        <h2><?php _e('Add conversion', 'cta'); ?></h2>

        <form method="post" action="<?php echo admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=conversion-table'); ?>">
            <?php wp_nonce_field('insert_conversion', 'cta'); ?>

            <table id="cta_add_param_table">
                <tr>
                    <td>
                        <?php _e('Conversion type', 'cta'); ?>
                    </td>
                    <td>
                        <select name="conversion_type">
                            <?php foreach($this->getConversionTypes() as $type): ?>
                                <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php _e('Circumference', 'cta'); ?>
                    </td>
                    <td>
                        <input type="text" name="conversion_circumference" />
                    </td>
                </tr>

                <tr>
                    <td>
                        <?php _e('Diameter', 'cta'); ?>
                    </td>
                    <td>
                        <input type="text" name="conversion_diameter" />
                    </td>
                </tr>

            </table>

            <br>

            <input type="submit" name="insert_conversion" value="<?php _e('Save'); ?>" class="button button-primary">

        </form>



    </div>

</div>