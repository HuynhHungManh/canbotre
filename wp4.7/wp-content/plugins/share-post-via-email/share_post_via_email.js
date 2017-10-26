jQuery(function ($) {
    // member subscribe
    function member_feedback(formId) {
        //Messages
        var warnings = {
            email: 'Địa chỉ email không hợp lệ.',
            textarea: 'Bạn chưa nhập tên của bạn',
            textarea_length: 'Tên bạn nhập không chính xác.',
            captcha: 'Nhập mã xác nhận ở trên.',
            confirm: 'Mã xác nhận không chính xác'
        };
        var validate = true;
        $('#' + formId + ' .required-label').remove();
        $('#' + formId + ' .success').remove();
        $('#' + formId + ' .required').each(function () {
            var myself = $(this);
            var capt = $("#" + formId + " .captcha_image").text();
            var capt_confirm = $("#" + formId + " #captcha").val();
            //email
            var emailRegex = new RegExp(/^([\w\.\-]+)@([\w\-]+)((\.(\w){2,3})+)$/i);
            if (myself.prop("type").toLowerCase() === 'email' && (!emailRegex.test(myself.val()))) {
                $(this).after('<div class="required-label">' + warnings.email + '</div>').addClass('required-active');
                validate = false;
            }
            //textarea
            if (myself.val() === '' && myself.hasClass('name-cs')) {
                myself.after('<div class="required-label">' + warnings.textarea + '</div>').addClass('required-active');
                validate = false;
            } else if (myself.prop("type").toLowerCase() === 'textarea' && myself.val().length <= 1) {
                myself.after('<div class="required-label">' + warnings.textarea_length + '</div>').addClass('required-active');
                validate = false;
            } else if (myself.val() === '' && myself.hasClass('captcha')) {
                myself.after('<div class="required-label">' + warnings.captcha + '</div>').addClass('required-active');
                validate = false;
            } else if (capt_confirm !== capt && myself.hasClass('captcha')) {
                myself.after('<div class="required-label">' + warnings.confirm + '</div>').addClass('required-active');
                validate = false;
            }
        });

        if (validate) {
            $('#' + formId + ' .loading-container').show();
            $('#' + formId + ' #send').prop('disabled', true);
            var formData = new FormData();
            var email = $('#' + formId + ' #email').val();
            var name = $('#' + formId + ' #name').val();
            var link = $('#' + formId + ' #link').val();
            var title = $('#' + formId + ' #title').val();
            var content = $('#' + formId + ' #content').val();
            var captcha = $('#' + formId + ' #captcha').val();
            formData.append('email', email);
            formData.append('name', name);
            formData.append('link', link);
            formData.append('title', title);
            formData.append('content', content);
            formData.append('captcha', captcha);
            $.ajax({
                url: '/wp-admin/admin-ajax.php?action=gg_share_post_via_email_ajax',
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    var message = response.data[0].message;
                    $('#' + formId + ' .loading-container').hide();
                    $('#chia-se-bv-form .group-btn').after(message);
                    $('#' + formId + ' #send').prop('disabled', false);
                }
            });
        }
    }

    $(document).on('click','#chia-se-bv-form #send',function($){
        member_feedback('chia-se-bv-form');
        return false;
    });

    $('.popup-chia-se .close-popup .close-popup-img').click(function(){
        $('.popup-chia-se').hide();
    });

    $('#chia-se-bv-form #captcha_refresh_share').click(function(){
        var formData = new FormData();
        formData.append('email', 'con_bird');
        $.ajax({
            url: '/wp-admin/admin-ajax.php?action=gg_share_post_get_captcha_ajax',
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                var img = response.data[0].img;
                $('#chia-se-bv-form .captcha_image').html('');
                $('#chia-se-bv-form .captcha_image').text('');
                $('#chia-se-bv-form .captcha_image').text(img);
                $('#chia-se-bv-form #capt').val(img);
            }
        });
    });
});
