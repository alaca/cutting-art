<?php defined('ABSPATH') or die('No direct script access!'); 

global $post;

$parameters = $this->getProductParameters($post->ID);

?>

<?php if($parameters): ?>

                
    <?php foreach($parameters as $parameter): ?>

        <div style="margin-bottom: 15px;">

            <label for="cta_parameter_<?php echo $parameter->id; ?>"><?php echo $parameter->name; ?></label> <br />

            <?php if($parameter->type == 'dropdown'): ?>

                <select id="cta_parameter_<?php echo $parameter->id; ?>" name="cta_parameters[<?php echo $parameter->id; ?>]" required>
                    <?php foreach($this->getParametersMeta($post->ID, $parameter->id) as $data): ?>
                        <option value="<?php echo $data->meta_value; ?>"><?php echo $data->meta_value; ?></option>
                    <?php endforeach; ?>
                </select>

            <?php elseif($parameter->type == 'numeric'): ?>

                <input id="cta_parameter_<?php echo $parameter->id; ?>" type="number" name="cta_parameters[<?php echo $parameter->id; ?>]" value="" required />

            <?php else: ?>

                <input id="cta_parameter_<?php echo $parameter->id; ?>" type="text" name="cta_parameters[<?php echo $parameter->id; ?>]" value="" required />

            <?php endif; ?>

        </div>


    <?php endforeach; ?>


<?php endif; ?>