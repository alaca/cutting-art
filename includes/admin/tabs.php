<?php defined('ABSPATH') or die('No direct script access!'); ?>

<h1>
    <?php echo CTA_PLUGIN_NAME; ?> 
    <span id="p18a_version"><?php echo CTA_VERSION; ?></span>
</h1>

<br />

<div id="p18a_tabs_menu">
    <ul>
        <li>
            <a href="<?php echo admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL); ?>" class="<?php if(is_null($this->get('tab'))) echo 'active'; ?>">
                <?php _e('Parameters', 'cta'); ?>
            </a>
        </li>
        <li>
            <a href="<?php echo admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=conversion-types'); ?>" class="<?php if($this->get('tab') == 'conversion-types') echo 'active'; ?>">
                <?php _e('Conversion types', 'cta'); ?>
            </a>
        </li>
        <li>
            <a href="<?php echo admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=conversion-table'); ?>" class="<?php if($this->get('tab') == 'conversion-table') echo 'active'; ?>">
                <?php _e('Conversion table', 'cta'); ?>
            </a>
        </li>

    </ul>
</div>