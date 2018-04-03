jQuery(document).ready(function ($) {

    $.ajaxSetup({
        method: 'POST',
        url: ajaxurl,
        dataType: 'json'
    });


    $(document).on('click', '#cta_parameter_add', function (e) {

        e.preventDefault();

        var self = $(this);
        var param = $('#cta_add_parameter').val();
        var inserted = $('[data-parameter-id="' + param + '"]');
        var post = $('#post_ID').val();

        // check if parameter is already inserted
        if (inserted.length > 0) {

            inserted.toggleClass('cta_group_border_focus');

            setTimeout(function(){
                inserted.toggleClass('cta_group_border_focus');
            }, 1000);

        } else {

            self.html('<img src="' + CTA.assetUrl + 'load.gif" />');

            $.ajax({
                data: {
                    action: 'cta_add_param',
                    param: param,
                    post: post 
                },
                dataType: 'html'
            }).done(function (response) {

                $('#cta_used_parameters').hide().html(response).fadeIn();

                self.html(CTA.add);
    
            });

        }




    });

    /**
     * Confirmation for deleteing
     */
    $(document).on('click', '.cta-delete', function (e) {
        return confirm(CTA.delete + ' ' + $(this).data('name')) ? true : false;
    });

    /**
     * Show hide remove parameter group link
     */
    $(document).on('mouseenter', '.cta_group', function () {
        $(this).find('.cta_remove_param').show();
    });

    $(document).on('mouseleave', '.cta_group', function () {
        $(this).find('.cta_remove_param').hide();
    });

    /**
     * Show hide remove parameter value link
     */
    $(document).on('mouseenter', '.cta_param_value', function () {
        $(this).find('.cta_remove_value').show();
    });

    $(document).on('mouseleave', '.cta_param_value', function () {
        $(this).find('.cta_remove_value').hide();
    });

    /**
     * Remove parameter group
     */
    $(document).on('click', '.cta_remove_param', function (e) {

        e.preventDefault();

        if (confirm(CTA.removeParameter + ' ' + $(this).data('name'))) {

            var self = $(this);

            $.ajax({
                data: {
                    action: 'cta_remove_param',
                    post_id: $('#post_ID').val(),
                    id: self.data('id')
                }
            }).done(function (response) {

                if (response.status != 0) {

                    self.parents('.cta_group').fadeOut(function () {
                        $(this).remove();
                    });

                } else {
                    console.log(response);
                }

            });

        }

    });

    /**
     * Remove parameter value
     */
    $(document).on('click', '.cta_remove_value', function (e) {

        e.preventDefault();

        if (confirm(CTA.removeValue + ' ' + $(this).data('name'))) {

            var self = $(this);

            $.ajax({
                data: {
                    action: 'cta_remove_value',
                    id: self.data('id')
                }
            }).done(function (response) {

                if (response.status != 0) {

                    self.parent('.cta_param_value').fadeOut(function () {
                        $(this).remove();
                    });

                } else {
                    console.log(response);
                }

            });
        }

    });

    /**
     * Remove added parameter value
     */
    $(document).on('click', '.cta_remove_add', function (e) {

        e.preventDefault();

        $(this).parent('.cta_param_value').remove();

    });

    /**
     * Add parameter value
     */
    $(document).on('click', '.cta_add_param_value', function (e) {

        e.preventDefault();

        var param_id = $(this).data('id'); // pass param id 

        $(this).before('<div class="cta_param_value" data-param-value="' + param_id + '"><input type="text" /> &nbsp; <a href="#" class="button cta_save_add" data-param_id="' + param_id + '">' + CTA.save + '</a><a href="#" class="button cta_remove_add">X</a></div>');


    });

    /**
     * Save added parameter value
     */
    $(document).on('click', '.cta_save_add', function (e) {

        e.preventDefault();

        var button = $(this);
        var remove_btn = $('.cta_remove_add');
        var post_id = $('#post_ID').val();
        var param_id = $(this).data('param_id');
        var value = $(this).parent('.cta_param_value').find('input').val();

        button.html('<img src="' + CTA.assetUrl + 'load.gif" />');
        remove_btn.hide();

        $.ajax({
            data: {
                action: 'cta_save_value',
                post: post_id,
                param: param_id,
                meta: value

            }
        }).done(function (response) {

            if (response.status == 1) {

                var row = '<div class="cta_param_value">' + value + "\n";
                row += '<a href="#" class="cta_remove_value" data-name="' + value + '" data-id="' + response.id + '">' + CTA.remove + '</a>';
                row += '</div>';

                $('[data-param-value="' + param_id + '"]').replaceWith(row);

            } else {
                console.log(response);
                button.html(CTA.save);
                remove_btn.show();
            }

        });

    });

});