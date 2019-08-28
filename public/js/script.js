(function() {
    var $;
    $ = this.jQuery || window.jQuery;
    win = $(window), body = $('body'), doc = $(document);

    $.fn.hc_accordion = function() {
        var acd = $(this);
        acd.find('ul>li').each(function(index, el) {
            if ($(el).find('ul li').length > 0) $(el).prepend('<button type="button" class="acd-drop"></button>');
        });
        acd.on('click', '.acd-drop', function(e) {
            e.preventDefault();
            var ul = $(this).nextAll("ul");
            if (ul.is(":hidden") === true) {
                ul.parent('li').parent('ul').children('li').children('ul').slideUp(180);
                ul.parent('li').parent('ul').children('li').children('.acd-drop').removeClass("active");
                $(this).addClass("active");
                ul.slideDown(180);
            } else {
                $(this).removeClass("active");
                ul.slideUp(180);
            }
        });
    }

    $.fn.hc_menu = function(options) {
        var settings = $.extend({
                open: '.open-mnav',
            }, options),
            this_ = $(this);
        var m_nav = $('<div class="m-nav"><button class="m-nav-close"><i class="icon_close"></i></button><div class="nav-ct"></div></div>');
        body.append(m_nav);

        m_nav.find('.m-nav-close').click(function(e) {
            e.preventDefault();
            mnav_close();
        });

        m_nav.find('.nav-ct').append(this_.children().clone());

        var mnav_open = function() {
            m_nav.addClass('active');
            body.append('<div class="m-nav-over"></div>').css('overflow', 'hidden');
        }
        var mnav_close = function() {
            m_nav.removeClass('active');
            body.children('.m-nav-over').remove();
            body.css('overflow', '');
        }

        doc.on('click', settings.open, function(e) {
            e.preventDefault();
            if (win.width() <= 1199) mnav_open();
        }).on('click', '.m-nav-over', function(e) {
            e.preventDefault();
            mnav_close();
        });

        m_nav.hc_accordion();
    }

    $.fn.hc_countdown = function(options) {
        var settings = $.extend({
                date: new Date().getTime() + 1000 * 60 * 60 * 24,
            }, options),
            this_ = $(this);

        var countDownDate = new Date(settings.date).getTime();

        var count = setInterval(function() {
            var now = new Date().getTime();
            var distance = countDownDate - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            this_.html('<div class="item"><span>' + days + '</span> ngày</div>' +
                '<div class="item"><span>' + hours + '</span> giờ</div>' +
                '<div class="item"><span>' + minutes + '</span> phút </div>' +
                '<div class="item"><span>' + seconds + '</span> giây </div>'
            );
            if (distance < 0) {
                clearInterval(count);
            }
        }, 1000);
    }

    $.fn.hc_upload = function(options) {
        var settings = $.extend({
                multiple: false,
                result: '.hc-upload-pane',
            }, options),
            this_ = $(this);

        var input_name = this_.attr('name');
        this_.removeAttr('name');

        this_.change(function(e) {
            if ($(settings.result).length > 0) {
                var files = event.target.files;
                if (settings.multiple) {
                    for (var i = 0, files_len = files.length; i < files_len; i++) {
                        var path = URL.createObjectURL(files[i]);
                        var name = files[i].name;
                        var size = Math.round(files[i].size / 1024 / 1024 * 100) / 100;
                        var type = files[i].type.slice(files[i].type.indexOf('/') + 1);

                        var img = $('<img src="' + path + '">');
                        var input = $('<input type="hidden" name="' + input_name + '[]"' +
                            '" value="' + path +
                            '" data-name="' + name +
                            '" data-size="' + size +
                            '" data-type="' + type +
                            '" data-path="' + path +
                            '">');
                        var elm = $('<div class="hc-upload"><button type="button" class="hc-del smooth">&times;</button></div>').append(img).append(input);
                        $(settings.result).append(elm);
                    }
                } else {
                    var path = URL.createObjectURL(files[0]);
                    var img = $('<img src="' + path + '">');
                    var elm = $('<div class="hc-upload"><button type="button" class="hc-del smooth">&times;</button></div>').append(img);
                    $(settings.result).html(elm);
                }
            }
        });

        body.on('click', '.hc-upload .hc-del', function(e) {
            e.preventDefault();
            this_.val('');
            $(this).closest('.hc-upload').remove();
        });
    }

}).call(this);


jQuery(function($) {
    var win = $(window),
        body = $('body'),
        doc = $(document);

    var FU = {
        get_Ytid: function(url) {
            var rx = /^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/;
            if (url) var arr = url.match(rx);
            if (arr) return arr[1];
        },
        get_currency: function(str) {
            if (str) return str.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },
        animate: function(elems) {
            var animEndEv = 'webkitAnimationEnd animationend';
            elems.each(function() {
                var $this = $(this),
                    $animationType = $this.data('animation');
                $this.addClass($animationType).one(animEndEv, function() {
                    $this.removeClass($animationType);
                });
            });
        },
    };

    var UI = {
        mMenu: function() {

        },
        header: function() {
            var elm = $('header'),
                h = elm.innerHeight(),
                offset = 200,
                mOffset = 0;
            var fixed = function() {
                elm.addClass('fixed');
                body.css('margin-top', h);
            }
            var unfixed = function() {
                elm.removeClass('fixed');
                body.css('margin-top', '');
            }
            var Mfixed = function() {
                elm.addClass('m-fixed');
                body.css('margin-top', h);
            }
            var unMfixed = function() {
                elm.removeClass('m-fixed');
                body.css('margin-top', '');
            }
            if (win.width() > 991) {
                win.scrollTop() > offset ? fixed() : unfixed();
            } else {
                win.scrollTop() > mOffset ? Mfixed() : unMfixed();
            }
            win.scroll(function(e) {
                if (win.width() > 991) {
                    win.scrollTop() > offset ? fixed() : unfixed();
                } else {
                    win.scrollTop() > mOffset ? Mfixed() : unMfixed();
                }
            });
        },
        backTop: function() {
            var back_top = $('.back-to-top'),
                offset = 800;

            back_top.click(function() {
                $("html, body").animate({ scrollTop: 0 }, 800);
                return false;
            });

            if (win.scrollTop() > offset) {
                back_top.fadeIn(200);
            }

            win.scroll(function() {
                if (win.scrollTop() > offset) back_top.fadeIn(200);
                else back_top.fadeOut(200);
            });
        },
        slider: function() {
            /*$('.slider-cas').slick({
            	nextArrow: '<img src="images/next.png" class="next" alt="Next">',
            	prevArrow: '<img src="images/prev.png" class="prev" alt="Prev">',
            })
            FU.animate($(".slider-cas .slick-current [data-animation ^= 'animated']"));
            $('.slider-cas').on('beforeChange', function(event, slick, currentSlide, nextSlide){
            	if(currentSlide!=nextSlide){
            		var aniElm = $(this).find('.slick-slide').find("[data-animation ^= 'animated']");
            		FU.animate(aniElm);
            	}
            });*/

            /*$('.pro-cas').slick({
	            slidesToShow: 4,
	            slidesToScroll: 1,
	            nextArrow: '<i class="cas-arrow smooth next"></i>',
	            prevArrow: '<i class="cas-arrow smooth prev"></i>',
	            dots: true,
	            autoplay: true,
	            swipeToSlide: true,
	            autoplaySpeed: 4000,
	            responsive: [
	            {
	                breakpoint: 1199,
	                settings: {
	                    slidesToShow: 3,
	                }
	            },
	            {
	                breakpoint: 991,
	                settings: {
	                    slidesToShow: 3,
	                }
	            },
	            {
	                breakpoint: 700,
	                settings: {
	                    slidesToShow: 2,
	                }
	            },
	            {
	                breakpoint: 480,
	                settings: {
	                    slidesToShow: 1,
	                }
	            }
	            ],
	        })*/

            /*$('.pro-cas').owlCarousel({
		        loop: true,
		        margin: 30,
		        responsiveClass:true,
		        nav: true,
		        smartSpeed: 800,
		        navText: ["<span class='smooth arrow-cas prev'></span>", "<span class='smooth arrow-cas next'></span>"],
		        responsive:{
		            992:{
		                items: 3,
		            },
		            768:{
		                items: 2,
		            },
		            0:{
		                items: 1,
		            }
		        }
		    })*/
            $('.cas-bn-home').owlCarousel({
                items: 1,
                loop: true,
                nav: false,
                dots: true,
                dotsClass: 'dots v3',
                autoplay: true,
                autoPlaySpeed: 5000,
                autoplayTimeout: 5000,
                smartSpeed: 800,
            })
            $('.cas-ads').owlCarousel({
                items: 1,
                loop: true,
                nav: false,
                dots: true,
                dotsClass: 'dots v2',
                autoplay: true,
                autoPlaySpeed: 5000,
                autoplayTimeout: 5000,
                smartSpeed: 800,
            })
            $('.sl-pro-related').owlCarousel({
                loop: false,
                responsiveClass: true,
                nav: true,
                dots: false,
                smartSpeed: 500,
                margin: 30,
                autoplay: true,
                /*slideBy: 4,*/
                autoplayTimeout: 5000,
                navClass: ["sl-arrow prev", "sl-arrow next"],
                navText: ["<i class='arrow_left_alt'></i>", "<i class='arrow_right_alt'></i>"],
                responsive: {
                    1199: {
                        items: 4,
                    },
                    991: {
                        items: 3,
                    },
                    479: {
                        items: 2,
                    },
                    0: {
                        items: 1,
                    }
                },
            });

            if($('.pro-img').length>0){
                $('.pro-img').slick({
                    arrows: false,
                    swipeToSlide: true,
                    asNavFor: '.pro-thumb',
                })
                $('.pro-thumb').slick({
                    slidesToShow: 7,
                    slidesToScroll: 1,
                    /*nextArrow: '<i class="fa fa-angle-right smooth next"></i>',
                    prevArrow: '<i class="fa fa-angle-left smooth prev"></i>',*/
                    autoplay: false,
                    swipeToSlide: true,
                    autoplaySpeed: 5000,
                    asNavFor: '.pro-img',
                    focusOnSelect: true,
                    responsive: [{
                            breakpoint: 1199,
                            settings: {
                                slidesToShow: 6,
                            }
                        },
                        {
                            breakpoint: 991,
                            settings: {
                                slidesToShow: 8,
                            }
                        },
                        {
                            breakpoint: 580,
                            settings: {
                                centerMode: false,
                                slidesToShow: 6,
                            }
                        },
                        {
                            breakpoint: 376,
                            settings: {
                                centerMode: false,
                                slidesToShow: 5,
                            }
                        }
                    ],
                });
            };
            if($('.set-content').length>0){
                $('.set-content').slick({
                    arrows: false,
                    swipeToSlide: true,
                    asNavFor: '.set-img',
                    fade: true,
                })
                $('.set-img').slick({
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    nextArrow: '<i class="arrow_right_alt smooth next"></i>',
                    prevArrow: '<i class="arrow_left_alt smooth prev"></i>',
                    autoplay: false,
                    swipeToSlide: true,
                    autoplaySpeed: 5000,
                    asNavFor: '.set-content',
                    focusOnSelect: true,
                    responsive: [{
                            breakpoint: 1199,
                            settings: {
                                slidesToShow: 3,
                            }
                        },
                        {
                            breakpoint: 991,
                            settings: {
                                slidesToShow: 2,
                            }
                        },
                        {
                            breakpoint: 479,
                            settings: {
                                centerMode: false,
                                slidesToShow: 1,
                            }
                        },
                    ],
                });
            };
            $('.sl-certifi').owlCarousel({
                loop: true,
                responsiveClass: true,
                nav: true,
                dots: false,
                smartSpeed: 500,
                margin: 30,
                autoplay: false,
                autoplayTimeout: 5000,
                navClass: ["slide-icon prev", "slide-icon next"],
                navText: ["<i class='arrow_left_alt'></i>", "<i class='arrow_right_alt'></i>"],
                responsive: {
                    1199: {
                        items: 5,
                    },
                    991: {
                        items: 4,
                    },
                    768: {
                        items: 3,
                    },
                    0: {
                        items: 2,
                    }
                }
            });
            $('.cas-pro-hot').owlCarousel({
                loop: false,
                responsiveClass: true,
                nav: true,
                dots: true,
                dotsClass: 'dots',
                smartSpeed: 500,
                margin: 30,
                autoplay: false,
                autoplayTimeout: 5000,
                navClass: ["slide-icon prev", "slide-icon next"],
                navText: ["<i class='arrow_left_alt'></i>", "<i class='arrow_right_alt'></i>"],
                responsive: {
                    1199: {
                        items: 4,
                    },
                    991: {
                        items: 3,
                    },
                    575: {
                        items: 2,
                    },
                    0: {
                        items: 1,
                    }
                }
            });
            $('.cas-cate-pro').owlCarousel({
                loop: false,
                responsiveClass: true,
                nav: true,
                dots: true,
                dotsClass: 'dots',
                smartSpeed: 500,
                margin: 30,
                autoplay: false,
                autoplayTimeout: 5000,
                navClass: ["slide-icon prev", "slide-icon next"],
                navText: ["<i class='arrow_left_alt'></i>", "<i class='arrow_right_alt'></i>"],
                responsive: {
                    1199: {
                        items: 4,
                    },
                    991: {
                        items: 3,
                    },
                    575: {
                        items: 2,
                    },
                    0: {
                        items: 1,
                    }
                }
            });
            $('.cas-member').owlCarousel({
                loop: false,
                responsiveClass: true,
                nav: true,
                dots: false,
                dotsClass: 'dots',
                smartSpeed: 500,
                margin: 30,
                autoplay: false,
                autoplayTimeout: 5000,
                navClass: ["slide-icon prev", "slide-icon next"],
                navText: ["<i class='arrow_left_alt'></i>", "<i class='arrow_right_alt'></i>"],
                responsive: {
                    1199: {
                        items: 5,
                    },
                    991: {
                        items: 4,
                    },
                    768: {
                        items: 3,
                    },
                    0: {
                        items: 3,
                    }
                }
            });
        },
        input_number: function() {
            doc.on('keydown', '.numberic', function(event) {
                if (!(!event.shiftKey &&
                        !(event.keyCode < 48 || event.keyCode > 57) ||
                        !(event.keyCode < 96 || event.keyCode > 105) ||
                        event.keyCode == 46 ||
                        event.keyCode == 8 ||
                        event.keyCode == 190 ||
                        event.keyCode == 9 ||
                        event.keyCode == 116 ||
                        (event.keyCode >= 35 && event.keyCode <= 39)
                    )) {
                    event.preventDefault();
                }
            });
            doc.on('click', '.i-number .up', function(e) {
                e.preventDefault();
                var input = $(this).parents('.i-number').children('input');
                var max = Number(input.attr('max')),
                    val = Number(input.val());
                if (!isNaN(val)) {
                    if (!isNaN(max) && input.attr('max').trim() != '') {
                        if (val >= max) {
                            return false;
                        }
                    }
                    input.val(val + 1);
                    input.attr('value',val+1);
                    input.trigger('number.up');
                }
            });
            doc.on('click', '.i-number .down', function(e) {
                e.preventDefault();
                var input = $(this).parents('.i-number').children('input');
                var min = Number(input.attr('min')),
                    val = Number(input.val());
                if (!isNaN(val)) {
                    if (!isNaN(min) && input.attr('max').trim() != '') {
                        if (val <= min) {
                            return false;
                        }
                    }
                    input.val(val - 1);
                    input.attr('value',val-1);
                    input.trigger('number.down');
                }
            });
        },
        yt_play: function() {
            doc.on('click', '.yt-box .play', function(e) {
                var id = FU.get_Ytid($(this).closest('.yt-box').attr('data-url'));
                $(this).closest('.yt-box iframe').remove();
                $(this).closest('.yt-box').append('<iframe src="https://www.youtube.com/embed/' + id + '?rel=0&amp;autoplay=1&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>');
            });
        },
        psy: function() {
            var btn = '.psy-btn',
                sec = $('.psy-section'),
                pane = '.psy-pane';
            doc.on('click', btn, function(e) {
                e.preventDefault();
                $(this).closest(pane).find(btn).removeClass('active');
                $(this).addClass('active');
                $("html, body").animate({ scrollTop: $($(this).attr('href')).offset().top - 40 }, 600);
            });

            var section_act = function() {
                sec.each(function(index, el) {
                    if (win.scrollTop() + (win.height() / 2) >= $(el).offset().top) {
                        var id = $(el).attr('id');
                        $(pane).find(btn).removeClass('active');
                        $(pane).find(btn + '[href="#' + id + '"]').addClass('active');
                    }
                });
            }
            section_act();
            win.scroll(function() {
                section_act();
            });
        },
        toggle: function() {
            var ani = 100;
            $('[data-show]').each(function(index, el) {
                var ct = $($(el).attr('data-show'));
                $(el).click(function(e) {
                    e.preventDefault();
                    ct.fadeToggle(ani);
                });
            });
            win.click(function(e) {
                $('[data-show]').each(function(index, el) {
                    var ct = $($(el).attr('data-show'));
                    if (ct.has(e.target).length == 0 && !ct.is(e.target) && $(el).has(e.target).length == 0 && !$(el).is(e.target)) {
                        ct.fadeOut(ani);
                    }
                });
            });
        },
        uiCounterup: function() {
            var item = $('.hc-couter'),
                flag = true;
            if (item.length > 0) {
                run(item);
                win.scroll(function() {
                    if (flag == true) {
                        run(item);
                    }
                });

                function run(item) {
                    if (win.scrollTop() + 70 < item.offset().top && item.offset().top + item.innerHeight() < win.scrollTop() + win.height()) {
                        count(item);
                        flag = false;
                    }
                }

                function count(item) {
                    item.each(function() {
                        var this_ = $(this);
                        var num = Number(this_.text().replace(".", ""));
                        var incre = num / 80;

                        function start(counter) {
                            if (counter <= num) {
                                setTimeout(function() {
                                    this_.text(FU.get_currency(Math.ceil(counter)));
                                    counter = counter + incre;
                                    start(counter);
                                }, 20);
                            } else {
                                this_.text(FU.get_currency(num));
                            }
                        }
                        start(0);
                    });
                }
            }
        },
        ready: function() {
            //UI.mMenu();
            //UI.header();
            UI.slider();
            UI.backTop();
            // UI.toggle();
            UI.input_number();
            //UI.uiCounterup();
            // UI.yt_play();
            // UI.psy();
        },
    }


    UI.ready();


    /*custom here*/
    $('.d-nav').hc_menu({
        open: '.open-mnav',
    })
    /*if($("[data-fancybox]").length){
        $("[data-fancybox]").fancybox({
            thumbs : {
                autoStart : true,
            }
        })
    }*/
    $('.ic-search').click(function(event) {
        $(this).children('i').toggleClass('icon_search icon_close_alt2');
        $('.head-search .form-search').toggleClass('show');
    });
    //menu header scroll
    $(window).scroll(function() {
        if ($(window).scrollTop() > 0) $('header').addClass('scroll');
        else $('header').removeClass('scroll toggle-menu');
    });

    if($(window).width()<767){
        $('footer .ft-item .title').click(function(event) {
            $(this).children('span').toggleClass('arrow_carrot-down arrow_carrot-up');
            $(this).next('.hd-mb').slideToggle(300);
        });
    }
    if(win.width() < 992){
        if($('#map').length){
            var vtmap = $('#map').offset().top;
            $('.map-item').click(function(event) {
                $("html, body").animate({ scrollTop: vtmap-120 }, 800 );
            });
        }
    }
    if ($(window).width() > 991) {
        if ($('.sb-news,.sb-product,.sb-account').length > 0) {
            $('.sb-news,.sb-product,.sb-account').stick_in_parent({
                offset_top: 110,
            });
        }
    }
    if ($('.head-user').length) {
        $('.head-user').click(function (event) {
            $(this).children('.nav-user').toggleClass('show');
        });
    }

    $(document).mouseup(function (e) {
        var sub = $('.head-user');
        if (!sub.is(e.target) && sub.has(e.target).length === 0) {
            $('.nav-user').removeClass('show');
        }
    });

})