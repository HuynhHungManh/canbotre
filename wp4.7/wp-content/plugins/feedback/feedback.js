jQuery(function ($) {
    // member subscribe
    function member_feedback(formId) {
        //Messages
        var warnings = {
            email: 'Địa chỉ email không hợp lệ.',
            textarea: 'Nội dung không được bỏ trống.',
            textarea_length: 'Nội dung quá ngắn, lớn hơn 3 ký tự.',
            captcha: 'Nhập mã xác nhận ở trên.',
            confirm: 'Mã xác nhận không chính xác'
        };
        var validate = true;
        $('#feedback .required-label').remove();
        $('#feedback .success').remove();
        $("#" + formId + " .required").each(function () {
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
            if (myself.prop("type").toLowerCase() === 'textarea' && myself.val() === '') {
                myself.after('<div class="required-label">' + warnings.textarea + '</div>').addClass('required-active');
                validate = false;
            } else if (myself.prop("type").toLowerCase() === 'textarea' && myself.val().length <= 3) {
                myself.after('<div class="required-label">' + warnings.textarea_length + '</div>').addClass('required-active');
                validate = false;
            } else if (myself.prop("type").toLowerCase() === 'text' && myself.val() === '') {
                myself.after('<div class="required-label">' + warnings.captcha + '</div>').addClass('required-active');
                validate = false;
            } else if (capt_confirm !== capt && myself.hasClass('captcha_confirm_submit')) {
                myself.after('<div class="required-label">' + warnings.confirm + '</div>').addClass('required-active');
                validate = false;
            }
        });
        if (validate) {
            $('#' + formId + ' .loading-container').show();
            $('#' + formId + ' #send').prop('disabled', true);
            var formData = new FormData();
            var email = $('#feedback #email').val();
            var content = $('#feedback #textarea_content').val();
            var captcha = $('#feedback #captcha').val();
            formData.append('email', email);
            formData.append('content', content);
            formData.append('captcha', captcha);
            $.ajax({
                url: '/wp-admin/admin-ajax.php?action=gg_add_feedback',
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    var message = response.data[0].message;
                    $('#' + formId + ' .loading-container').hide();
                    $('#feedback .group-btn').after(message);
                    $('#feedback #textarea_content').val('');
                    reFreshCaptcha();
                    $('#' + formId + ' #send').prop('disabled', false);
                }
            });
        }
    }

    $(document).on('click','#feedback #send',function($){
        member_feedback('feedback');
        return false;
    });

    $(document).on('click','#reset',function($){
        var strconfirm = confirm("Bạn có chắc chắn muốn xóa và nhập lại?");
        if(strconfirm===true){
            document.getElementById("email").value = '';
            document.getElementById("textarea_content").value = '';
            document.getElementById("email").focus();
        }
        return false;
    });

    function reFreshCaptcha() {
        $('#feedback #captcha').val('');
        var formData = new FormData();
        formData.append('email', 'con_bird');
        $.ajax({
            url: '/wp-admin/admin-ajax.php?action=gg_get_captcha_ajax',
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                var img = response.data[0].img;
                $('.feedback .captcha_image').html('');
                $('.feedback .captcha_image').text('');
                $('.feedback .captcha_image').text(img);
                $('.feedback #capt').val(img);
            }
        });
    }
    $('.feedback #captcha_refresh').click(function(){
        reFreshCaptcha();
    });
});
