jQuery(function ($) {
    $('.back-top').on('click', function() {
        $('body,html').animate({
            scrollTop: 0
        }, 800);
        return false;
    });
    $(window).scroll(function() {
        if ($(this).scrollTop() > 120) {
            $('.back-top').fadeIn();
        } else {
            $('.back-top').fadeOut();
        }
    });
    $('.share_email').on('click', function() {
        $('.popup-chia-se').show();
        return false;
    });
    $('.popup-chia-se .close-popup').on('click', function() {
        $('.popup-chia-se').hide();
        return false;
    });
    $(document).mouseup(function (e)
    {
        var container = $('#masthead');
        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            if($('.navbar-collapse').hasClass('in')) {
                $('.navbar-collapse').removeClass('in');
            }
        }
    });
    $('.group-search-login .site_search .btn_search').on('click', function() {
        var display = $('.group-search-login .site_search .text_search').css('display');
        if(display == 'none') {
            $('.group-search-login .site_search .text_search').show();
            $('.group-search-login .input-group .form-control').css(
                {
                    'border-radius':'4px 0px 0px 4px',
                    'border': '1px solid #a1a1a1'
                }
            );
            $('.group-search-login .input-group-btn .btn_search').css(
                {
                    'border-radius': '0px 4px 4px 0px',
                    'border-left': '0px'
                }
            );
            $('.group-search-login .site_search .text_search').focus();
            return false;
        } else {
            if($('.group-search-login .site_search .text_search').val() == '') {
                $('.group-search-login .site_search .required-label').remove();
                $('.group-search-login .site_search .text_search').focus();
                $('.group-search-login .site_search #search_rs').after('<div class="required-label">Vui lòng đánh từ khóa tìm kiếm</div>');
                return false;
            } else {
                return true;
            }
        }
        return false;
    });
    // $('#linking').on('change', function() {
    //     var value = $(this).val();
    //     if(value !== '---') {
    //         window.open(value, '_blank');
    //     }
    //     return false;
    // });
    // onclick popup
    function getPopup(url) {
        var screen = $(window).width()/2;
        var left = screen - screen/2;
        var left = screen - screen/2 + left/2;
        var left = screen - screen/2 + left/2 - left/3;
        window.open(url, "_blank", "menubar=no, toolbar=no, scrollbars=no, resizable=no, width=550, height=500, top=50, left="+left);
        return false;
    }

    $('.share_google a').click(function(){
        var url = $(this).attr('href');
        getPopup(url);
        return false;
    });
    $('.share_facebook a').click(function(){
        var url = $(this).attr('href');
        getPopup(url);
        return false;
    })

    //get heightest of div 4 column in panel newst home page
    function getHeighestOfDiv() {
        var windowWidth = $(window).width();
        if(windowWidth >= 970) {
            var heights = $(".content-home .row .col-md-6 .panel").map(function ()
            {
                return $(this).height();
            }).get(),
                maxHeight = Math.max.apply(null, heights);
            $(".content-home .row .col-md-6 .panel").css({'height': maxHeight});
        }
    }

    getHeighestOfDiv();
    $(window).resize(function () {
        $(".content-home .row .col-md-6 .panel").css({'height': 'inherit'});
        getHeighestOfDiv();
    });

    $('.popup-detail-member a').on('click', function() {
        var href = $(this).attr('href');
        getPopup(href);
        return false;
    });

    $('.list-notify').slick({
        dots: false,
        infinite: false,
        speed: 1000,
        slidesToShow: 1,
        slidesToScroll: 1,
        responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            dots: false
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
        ]
    });
});


