<?php defined('ABSPATH') or die('No direct script access!'); ?>

<div class="wrap">

    <?php require CTA_ADMIN_DIR . 'tabs.php'; ?>

    <div class="p18a-page-wrapper">

        <?php

            $list = new CuttingArt\ListTable();

            $list->columns([
                'name' => __('Name', 'cta'),
                'priority_id' => __('Priority ID', 'cta'),
                'type' => __('Type', 'cta'),
                'use_foreach' => __('Available for all products', 'cta'),
                'use_conversion' => __('Use conversion table', 'cta'),
                'actions' => __('Actions', 'cta')
            ]);


            $list->filter(['use_foreach', 'use_conversion'], function($item, $name) {

                return $item[$name] ? __('Yes') :  __('No');

            });
            
            $list->filter('actions', function($item, $name) {

                $edit_url = admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=edit_parameter&parameter=' .  $item['id']);
                $values_url = admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=default_values&parameter=' .  $item['id']);
                $delete_url = wp_nonce_url(admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&delete=' .  $item['id']), 'delete_param', 'cta');

                $links = '<a href="' . $edit_url . '" class="button">' . __('Edit', 'cta') . '</a> &nbsp; ';                
                $links .= '<a href="' . $delete_url . '" class="button cta-delete" data-name="' . $item['name'] . '">' . __('Delete', 'cta') . '</a> &nbsp; ';
                
                if ($item['use_foreach'] && $item['type'] == 'dropdown') {

                    $defaults = $this->getDefaultsMeta($item['id']);

                    $links .= '<a href="' . $values_url . '" class="button">' . __('Defaults', 'cta') . ' (' . count($defaults) . ')' . '</a>';
                }

                return $links;

            });

            $data = $GLOBALS['wpdb']->get_results('SELECT * FROM ' . $GLOBALS['wpdb']->prefix . 'cta_parameters', ARRAY_A);
                
            $list->show($data);

        ?>

        <a href="<?php echo admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=add_parameter'); ?>" class="button button-primary"><?php _e('Add parameter', 'cta'); ?></a>

    </div>

</div>