<?php defined('ABSPATH') or die('No direct script access!'); ?>

<div class="wrap">

    <?php require CTA_ADMIN_DIR . 'tabs.php'; ?>

    <div class="p18a-page-wrapper">

        <?php

            $list = new CuttingArt\ListTable();

            $list->columns([
                'name' => __('Name', 'cta'),
                'code' => __('Code', 'cta'),
                'actions' => __('Actions', 'cta')
            ]);
            
            $list->filter('actions', function($item, $name) {

                $edit_url = admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=edit-type&type=' .  $item['id']);
                $delete_url = wp_nonce_url(admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=conversion-types&delete=' .  $item['id']), 'delete_conversion_type', 'cta');

                return '<a href="' . $edit_url . '" class="button">' . __('Edit', 'cta') . '</a> &nbsp; ' . 
                       '<a href="' . $delete_url . '" class="button cta-delete" data-name="' . $item['name'] . '">' . __('Delete', 'cta') . '</a>';
            });

            $data = $GLOBALS['wpdb']->get_results('SELECT * FROM ' . $GLOBALS['wpdb']->prefix . 'cta_types', ARRAY_A);
                
            $list->show($data);

        ?>

        <a href="<?php echo admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=add-type'); ?>" class="button button-primary"><?php _e('Add conversion type', 'cta'); ?></a>

    </div>

</div>