<?php defined('ABSPATH') or die('No direct script access!'); ?>

<div class="wrap">

    <?php require CTA_ADMIN_DIR . 'tabs.php'; ?>

    <div class="p18a-page-wrapper">

        <?php

            $list = new CuttingArt\ListTable();

            $list->columns([
                'name' => __('Conversion type', 'cta'),
                'circumference' => __('Circumference', 'cta'),
                'diameter' => __('Diameter', 'cta'),
                'actions' => __('Actions', 'cta')
            ]);
            
            $list->filter('actions', function($item, $name) {

                $edit_url = admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=edit-conversion&id=' .  $item['cid']);
                $delete_url = wp_nonce_url(admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=conversion-table&delete=' .  $item['cid']), 'delete_conversion', 'cta');

                return '<a href="' . $edit_url . '" class="button">' . __('Edit', 'cta') . '</a> &nbsp; ' . 
                       '<a href="' . $delete_url . '" class="button cta-delete" data-name="' . $item['name'] . '">' . __('Delete', 'cta') . '</a>';
            });

            global $wpdb;

            $data = $wpdb->get_results('
                SELECT *,' . $wpdb->prefix . 'cta_conversions.id as cid  FROM ' . $wpdb->prefix . 'cta_conversions, ' . $wpdb->prefix . 'cta_types
                WHERE ' . $wpdb->prefix . 'cta_conversions.type_id = ' . $wpdb->prefix . 'cta_types.id', 
                ARRAY_A
            );

            $list->show($data);

        ?>

        <a href="<?php echo admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=add-conversion'); ?>" class="button button-primary"><?php _e('Add conversion', 'cta'); ?></a>

    </div>

</div>