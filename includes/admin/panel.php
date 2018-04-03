<?php defined('ABSPATH') or die('No direct script access!'); ?>
<div id="cta_parameters" class="panel woocommerce_options_panel">

    <div class="options_group">
        <p class="form-field dimensions_field">

            <?php if(!empty($parameters = $this->getParameters())): ?>

                <label for="cta_add_parameter" class="cta_label"><?php _e('Add parameter', 'cta'); ?></label>

                <select id="cta_add_parameter" class="select short">
                    <?php foreach($parameters as $parameter): ?>
                        <option value="<?php echo $parameter->id; ?>"><?php echo $parameter->name; ?></option>
                    <?php endforeach; ?>

                </select>

                &nbsp; 

                <a class="button" id="cta_parameter_add"><?php _e('Add', 'cta'); ?></a>

            <?php else: ?>

                <?php _e('There is no parameters at the time', 'cta'); ?>, <a href="<?php echo admin_url('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=add_parameter'); ?>"><?php _e('Add parameters', 'cta'); ?></a>
            
            <?php endif; ?>

        </p>
    </div>

    <div id="cta_used_parameters">

    <?php foreach($this->getProductParameters($this->get('post')) as $parameter): ?>

        <div class="options_group cta_group">

            <div data-parameter-id="<?php echo $parameter->id; ?>" class="cta_border">

                <a href="#" class="cta_remove_param" data-id="<?php echo $parameter->id; ?>" data-name="<?php echo $parameter->name; ?>"><?php _e('Remove', 'cta'); ?></a>

                <div class="cta_meta_group">

                    <strong class="cta_meta_title">
                        <?php echo $parameter->name; ?>
                    </strong>

                    <?php if($parameter->type == 'dropdown'): ?>

                        <?php foreach($this->getParametersMeta($this->get('post'), $parameter->id) as $data): ?>

                            <div class="cta_param_value">
                                <?php echo $data->meta_value; ?>
                                <a href="#" class="cta_remove_value" data-id="<?php echo $data->id; ?>" data-name="<?php echo $data->meta_value; ?>"><?php _e('Remove', 'cta'); ?></a>
                            </div>

                        <?php endforeach; ?>

                        <a href="#" class="cta_add_param_value" data-id="<?php echo $parameter->id; ?>"><?php _e('Add', 'cta'); ?></a>

                    <?php endif; ?>

                </div>

            </div>

        </div>


    <?php endforeach; ?>

    </div>


</div>