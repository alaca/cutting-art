<?php defined('ABSPATH') or die('No direct script access!'); ?>
<?php foreach($this->getProductParameters($this->post('post')) as $parameter): ?>

    <div class="options_group cta_group">

        <div data-parameter-id="<?php echo $parameter->id; ?>" class="cta_border">

            <a href="#" class="cta_remove_param" data-id="<?php echo $parameter->id; ?>" data-name="<?php echo $parameter->name; ?>"><?php _e('Remove', 'cta'); ?></a>

            <div class="cta_meta_group">

                <strong class="cta_meta_title">
                    <?php echo $parameter->name; ?>
                </strong>

                <?php if($parameter->type == 'dropdown'): ?>

                    <?php foreach($this->getParametersMeta($this->post('post'), $parameter->id) as $data): ?>

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
