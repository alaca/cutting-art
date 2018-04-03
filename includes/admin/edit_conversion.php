<?php defined('ABSPATH') or die('No direct script access!'); ?>

<div class="wrap">

    <?php require CTA_ADMIN_DIR . 'tabs.php'; ?>

    <div class="p18a-page-wrapper">

        <h2><?php _e('Edit conversion', 'cta'); ?></h2>

        <form name="edit_conversion" method="post" action="<?php echo admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=conversion-table'); ?>">

            <?php wp_nonce_field('edit_conversion', 'cta'); ?>
            
            <input type="hidden" name="conversion_id" value="<?php echo $data->id; ?>" />

            <table id="cta_add_param_table">

                <tr>
                    <td>
                        <?php _e('Conversion type', 'cta'); ?>
                    </td>
                    <td>
                        <select name="conversion_type">
                            <?php foreach($this->getConversionTypes() as $type): ?>
                                <option value="<?php echo $type->id; ?>" <?php if($data->type_id == $type->id) echo 'selected'; ?>>
                                    <?php echo $type->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php _e('Circumference', 'cta'); ?>
                    </td>
                    <td>
                        <input type="text" name="conversion_circumference" value="<?php echo $data->circumference; ?>" />
                    </td>
                </tr>

                <tr>
                    <td>
                        <?php _e('Diameter', 'cta'); ?>
                    </td>
                    <td>
                        <input type="text" name="conversion_diameter" value="<?php echo $data->diameter; ?>" />
                    </td>
                </tr>

            </table>

            <br>

            <input type="submit" name="edit_conversion" value="<?php _e('Save'); ?>" class="button button-primary">

        </form>



    </div>

</div>