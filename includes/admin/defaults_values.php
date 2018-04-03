<?php defined('ABSPATH') or die('No direct script access!'); ?>

<div class="wrap">

    <?php require CTA_ADMIN_DIR . 'tabs.php'; ?>

    <div class="p18a-page-wrapper">

        <br />

        <h3><?php printf(__('Parameter %s default values', 'cta'), $parameter->name); ?></h3>

        <?php

            $list = new CuttingArt\ListTable();

            $list->columns([
                'meta_value' => __('Name', 'cta'),
                'actions' => __('Actions', 'cta')
            ]);
            
            $list->filter('actions', function($item, $name) {

                $delete_url = wp_nonce_url(admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=default_values&parameter=' . $this->get('parameter') . '&delete=' .  $item['id']), 'delete_default_value', 'cta');
                return '<a href="' . $delete_url . '" class="button cta-delete" data-name="' . $item['meta_value'] . '">' . __('Delete', 'cta') . '</a>';

            });

            
            $data = $GLOBALS['wpdb']->get_results('SELECT * FROM ' . $GLOBALS['wpdb']->prefix . 'cta_defaults_meta WHERE param_id = ' . intval($this->get('parameter')), ARRAY_A);

                
            $list->show($data);

        ?>

        <form method="post" action="<?php echo admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=default_values&parameter=' . $parameter->id); ?>">
            <?php wp_nonce_field('insert_defaults_value', 'cta'); ?>
            <label for="param_value"><?php _e('Add value', 'cta'); ?></label> <br /><br />
            <input type="text" name="param_value" id="param_value" placeholder="<?php _e('Value', 'cta'); ?>" class="cta_input_short" />
            <input type="hidden" name="param_id" value="<?php echo $parameter->id; ?>" />
            <input type="submit" name="insert_defaults_value" value="<?php _e('Add'); ?>" class="button button-primary">
        </form>

    </div>

</div>