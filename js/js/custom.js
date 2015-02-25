$jq(function () {
    $jq('.top-block > li').mouseenter(function () {
        $jq(this).children('ul').show()
    }).mouseleave(function () {
        $jq(this).children('ul').hide()
    })
});
$jq(function () {
    $jq('.block-cart').mouseenter(function () {
        $jq(this).find('.block-content').show()
    }).mouseleave(function () {
        $jq(this).find('.block-content').hide()
    });
    $jq('#mini-cart .details').click(function () {
        $jq(this).parent('div').find('.item-options').toggle()
    })
});


$jq(function () {
    var catAmt = 10;
    if (typeof catAmt != 'undefined') {
        var exclude = $jq("ul#nav li.level0:gt(" + catAmt + ")");
	
        $jq("ul#nav li.level0:gt(" + catAmt + ")").hide();
        $jq('ul#nav li.more-cat').click(function () {
            $jq(exclude).insertBefore($jq(this)).slideToggle();
            $jq(this).toggleClass('active');
            $jq(this).hasClass('active') ? $jq(this).find('a').text('Minimize List') : $jq(this).find('a').text('More Categories')
        });
        $jq('#nav').mouseleave(function () {
            $jq(exclude).slideUp();
            $jq('ul#nav li.more-cat').removeClass('active').find('a').text('More Categories')
        })
    }
    $jq('#nav > li,#topnav > li').mouseenter(function () {
        $jq(this).children('ul,.html-block').show()
    }).mouseleave(function () {
        $jq(this).children('ul,.html-block').hide()
    })
});
$jq(function () {
    $jq('.slider').hide()
});
$jq(window).load(function () {
    function equalHeights(sel1, sel2, sel3, diff1, diff2) {
        $jq(sel1).each(function () {
            var left = $jq(this).find(sel2).outerHeight();
            var right = $jq(this).find(sel3).outerHeight(true);
            if (left > right) {
                var tallest = left;
                $jq(this).find(sel3).css("height", left - diff1 + "px")
            } else {
                var tallest = right;
                $jq(this).find(sel2).css("height", right - diff2 + "px")
            }
        })
    }
    equalHeights('#nav li.hasBlock', 'ul.level0', '.html-block', 30, 20);
    equalHeights('.catProd li > ul > li', '.left', '.right', 2, 32);
    equalHeights('.information', '.about-block', '.block-subscribe', 40, 40);
    equalHeights('.footer', '.footer-links', '.footer-block', 40, 40);
    var h = $jq('.footer-links').outerHeight();
    $jq('.social-links').css("height", h - 40 + "px");
    $jq('.nav-breadcrumbs .main-categories').mouseenter(function () {
        $jq(this).find('h3').addClass('active');
        $jq('ul#topnav').show();
        equalHeights('#topnav li.hasBlock', 'ul.level0', '.html-block', 30, 20)
    }).mouseleave(function () {
        $jq('ul#topnav').hide();
        $jq(this).find('h3').removeClass('active')
    });
    equalHeights('.products-list li', '.product-image', '.product-shop', 20, 0);
    $jq('.slider').show();
    $jq('.slider').find('ul:first-child').addClass('slides');

    function slideshow(slider, auto, liWidth, mini, maxi, margin, direction, pagination) {
        $jq(slider).flexslider({
            namespace: "slides-",
            animation: 'slide',
            slideshow: auto,
            slideshowSpeed: 5000,
            touch: true,
            itemWidth: liWidth,
            minItems: mini,
            maxItems: maxi,
            itemMargin: margin,
            pauseOnHover: true,
            directionNav: direction,
            controlNav: pagination,
            prevText: "",
            nextText: "",
            useCSS: false,
            controlsContainer: "",
        })
    }
    slideshow('div.latest .block-slide', false, 170, 2, 4, 10, true, false);
    slideshow('div.featured .block-slide', false, 170, 2, 4, 10, true, false);
    slideshow('div.bestseller .block-slide', false, 170, 1, 1, 10, false, true);
    slideshow('div.more-views .block-slide', false, 127, 1, 2, 10, false, true);
    slideshow('div.block-related .block-slide', false, 170, 1, 1, 10, false, true);
    slideshow('div.featured-categories .block-slide', false, 918, 1, 4, 0, true, false);
    slideshow('div.box-up-sell .block-slide', false, 300, 1, 3, 6, true, false);
    slideshow('div.slider', true, '', '', '', 10, false, true);
    $jq('.slider .slides-control-nav,.bestseller .slides-control-nav,.more-views .slides-control-nav').prepend('<li><a href="prev" class="prev arrow">prev</a></li>').append('<li><a href="next" class="next arrow">next</a></li>');
    $jq('.slider a.prev,.slider a.next,.bestseller a.prev,.bestseller a.next,.more-views a.prev,.more-views a.next').click(function () {
        var href = $jq(this).attr('href');
        if ($jq(this).is('.slider a.prev,.slider a.next')) {
            $jq('.slider').flexslider(href)
        } else {
            $jq('.bestseller .block-slide,.more-views .block-slide').flexslider(href)
        }
        return false
    });
    $jq('.block-slide .slides-direction-nav li a').prepend('<span />');
    equalHeights('.catalog-product-view .product-shop', '.product-details', '.product-img-box', 25, 1);
    equalHeights('.catalog-product-view .product-shop', '.product-details', '.custom-block', 37, 1)
});
$jq(function () {
    $jq('.catProd > li > ul > li').mouseenter(function () {
        var ul = $jq(this).closest('ul');
        $jq(ul).find('.right').hide();
        $jq(ul).find('> li').css('marginRight', '10px');
        $jq(this).css('marginRight', '210px').find('.right').show()
    })
});
$jq(function () {
    $jq('.wrap .tier').mouseenter(function () {
        $jq(this).find('.tier-prices').show()
    }).mouseleave(function () {
        $jq(this).find('.tier-prices').hide()
    })
});
$jq(function () {
    $jq('.product-tabs > .box-collateral').first().show();
    $jq('.product-tabs').prepend('<ul class="tabs" />');
    $jq('.product-tabs > .box-collateral > h2').each(function () {
        var lis = $jq(this).text();
        $jq('ul.tabs').append('<li><a href="#">' + lis + '</a></li>')
    });
    $jq('ul.tabs li').first().addClass('active');
    $jq('ul.tabs li a').click(function (e) {
        e.preventDefault()
    });
    $jq('ul.tabs li a').mouseenter(function (e) {
        var index = $jq('ul.tabs li a').index(this);
        $jq('ul.tabs li').removeClass('active');
        $jq(this).parent('li').addClass('active');
        $jq('.product-tabs > .box-collateral').hide();
        $jq('.product-tabs > .box-collateral:eq(' + index + ')').show()
    })
});
$jq(function () {
    $jq('.rating-links a,div.no-ratings a').click(function (e) {
        $jq('.box-collateral').hide();
        $jq('ul.tabs li').removeClass('active');
        $jq('.box-collateral.reviews,.box-collateral.box-reviews').show();
        $jq('ul.tabs li:eq(2)').addClass('active')
    })
});
$jq(function () {
    $jq('a.reviewBtn').click(function (e) {
        e.preventDefault();
        $jq('.reviews .form-add').stop(true, true).slideToggle()
    })
});
$jq(function () {
    $jq("ul.qty-amt li a").click(function () {
        if ($jq(this).hasClass("plus")) {
            var qty = $jq("#qty").val();
            qty++;
            $jq("#qty").val(qty)
        } else {
            var qty = $jq("#qty").val();
            qty--;
            if (qty > 0) {
                $jq("#qty").val(qty)
            }
        }
    })
});
/*$jq(function () {
    $jq('.footer .fooLinks').wrapAll('<div class="footer-links" />')
});*/
$jq(function () {
    $jq('.more-views, .product-img-box .product-image').magnificPopup({
        delegate: 'a.mImage',
        type: 'image',
        tLoading: 'Loading image #%curr%...',
        mainClass: 'mfp-img-mobile',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0, 1]
        },
        zoom: {
            enabled: true,
            duration: 300
        },
        image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
        }
    })
});
$jq(function () {
    $jq("#hidden-nav").tinyNav();
    $jq(".header .tinynav").wrap('<div class="select-nav" />');
    $jq(".header .select-nav select").wrap('<div class="select-wrap" />')
});
$jq(function () {
    $jq('.toggleDiv').click(function () {
        $jq('.search-block,.block-cart,.select-nav').toggle()
    })
});