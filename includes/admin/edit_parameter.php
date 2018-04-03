<?php defined('ABSPATH') or die('No direct script access!'); ?>

<div class="wrap">

    <?php require CTA_ADMIN_DIR . 'tabs.php'; ?>

    <div class="p18a-page-wrapper">

        <h2><?php _e('Edit parameter', 'cta'); ?></h2>

        <form id="cta_parameters_form_edit" name="cta_parameters_form_edit" method="post" action="<?php echo admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL); ?>">

            <?php wp_nonce_field('edit_param', 'cta'); ?>
            
            <input type="hidden" name="param_id" value="<?php echo $data->id; ?>" />

            <table id="cta_add_param_table">
                <tr>
                    <td>
                        <?php _e('Priority ID', 'cta'); ?>
                    </td>
                    <td>
                        <input type="text" name="param_priority" value="<?php echo $data->priority_id; ?>" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php _e('Parameter name', 'cta'); ?>
                    </td>
                    <td>
                        <input type="text" name="param_name" value="<?php echo $data->name; ?>" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php _e('Parameter type', 'cta'); ?>
                    </td>
                    <td>
                        <select name="param_type">
                            <option value="dropdown" <?php if($data->type == 'dropdown') echo 'selected'; ?>><?php _e('Dropdown', 'cta'); ?></option>
                            <option value="text" <?php if($data->type == 'text') echo 'selected'; ?>><?php _e('Text', 'cta'); ?></option>
                            <option value="numeric" <?php if($data->type == 'numeric') echo 'selected'; ?>><?php _e('Numeric', 'cta'); ?></option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>
                        <?php _e('Use conversion table', 'cta'); ?>
                    </td>
                    <td>
                        <input type="checkbox" name="param_conversion" value="1" <?php if($data->use_conversion) echo 'checked'; ?> />
                    </td>
                </tr>

                <tr>
                    <td>
                        <?php _e('Available for all products', 'cta'); ?>
                    </td>
                    <td>
                        <input type="checkbox" name="param_foreach" value="1" <?php if($data->use_foreach) echo 'checked'; ?> />
                    </td>
                </tr>

            </table>

            <br>

            <input type="submit" name="edit_param" value="<?php _e('Save'); ?>" class="button button-primary">

        </form>



    </div>

</div>