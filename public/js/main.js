$(document).ready(function () {
  $('.headercontent').removeClass('menu-open');
  $('body').removeClass('blurred');
  function handleHoverEffect() {
      if (window.innerWidth > 992) { 
        $('header .dropdown').hover(
          function () {
            $('.dropdown').removeClass('active');
              $('header .dropdown-menu').css({
                'visibility': 'hidden',
                'opacity': '0',
                'display': 'none'
              });
              $('.headercontent').addClass('menu-open');
              $('body').addClass('blurred');
              $(this).find('.dropdown-menu').css({
                  'visibility': 'visible',
                  'opacity': '1',
                  'display': 'flex'
              });
          },
          function () {
              setTimeout(() => {
                  if (!$('header .dropdown:hover, header .dropdown-menu:hover').length) {
                      $('.headercontent').removeClass('menu-open');
                      $('body').removeClass('blurred');
                      $('.dropdown-menu').css({
                          'visibility': 'hidden',
                          'opacity': '0',
                          'display': 'none'
                      });
                  }
              }, 200);
          }
        );

        $('header .dropdown-menu').mouseleave(function () {
            setTimeout(() => {
                if (!$('header .dropdown:hover, header .dropdown-menu:hover').length) {
                    $('.headercontent').removeClass('menu-open');
                    $('body').removeClass('blurred');
                    $('.dropdown-menu').css({
                        'visibility': 'hidden',
                        'opacity': '0',
                        'display': 'none'
                    });
                   
                }
                $('.dropdown').removeClass('active');
            }, 200);
        });
        $('header .dropdown').off('click').on('click', function (e) {
          e.preventDefault();
          let parentDropdown = $(this);
          let dropdownMenu = parentDropdown.find('.dropdown-menu');

          if (!parentDropdown.hasClass('active')) {
              $('header .dropdown').removeClass('active');
              $('header .dropdown-menu').css({
                  'visibility': 'hidden',
                  'opacity': '0',
                  'display': 'none'
              });
              $('.headercontent').removeClass('menu-open');
              $('body').removeClass('blurred');

              parentDropdown.addClass('active');
              $('.headercontent').addClass('menu-open');
              $('body').addClass('blurred');
              dropdownMenu.css({
                  'visibility': 'visible',
                  'opacity': '1',
                  'display': 'flex'
              });
          } else {
            let isDropdown = $(e.target).closest('.dropdown').length;
            let isDropdownMenu = $(e.target).closest('.dropdown-menu').length;
            if (isDropdown || isDropdownMenu) {
                return;
            }
              parentDropdown.removeClass('active');
              $('.headercontent').removeClass('menu-open');
              $('body').removeClass('blurred');
              dropdownMenu.css({
                  'visibility': 'hidden',
                  'opacity': '0',
                  'display': 'none'
              });
          }
        });

        $(document).on('click', function (e) {
            let isDropdown = $(e.target).closest('.dropdown').length;
            let isDropdownMenu = $(e.target).closest('.dropdown-menu').length;
            if (isDropdown || isDropdownMenu) {
                return;
            }
            
                $('.dropdown').removeClass('active');
                $('.headercontent').removeClass('menu-open');
                $('body').removeClass('blurred');
                $('.dropdown-menu').css({
                    'visibility': 'hidden',
                    'opacity': '0',
                    'display': 'none'
                });
        });
      } else {
        let isDropdown = $(e.target).closest('.dropdown').length;
        let isDropdownMenu = $(e.target).closest('.dropdown-menu').length;
        if (isDropdown || isDropdownMenu) {
            return;
        }
          $('.dropdown').removeClass('active');
          $('header .dropdown').off('mouseenter mouseleave');
          $('.headercontent').removeClass('menu-open');
          $('body').removeClass('blurred');
          $('header .dropdown-menu').css({
              'visibility': 'hidden',
              'opacity': '0',
              'display': 'none'
          });
      }
     
  }
  handleHoverEffect();

  $(window).resize(handleHoverEffect);
});
$(document).ready(function () {
  $(window).on("scroll", function () {
      if ($(window).scrollTop() > 50) { 
          $("header").addClass("sticky");
      } else {
          setTimeout(() => {
              $("header").removeClass("sticky");
          }, 300); 
      }
  });
});


$(document).ready(function () {
  
  function isMobile() {
      return window.innerWidth <= 992; 
  }
  $('.dropdownwithdropdownitem').click(function (e) {
    e.preventDefault();
      
      var tagid = $(this).data('tag');
      var $innertab = $(this).closest('.innertab');
      if (isMobile()) {
        console.log("mobile hit");
          $(".dropdownwithdropdownitem").removeClass("activelink");
         
          var $list = $(this).next('.list');
          $('.list').not($list).slideUp().addClass('hide').prev('.dropdownwithdropdownitem').removeClass("activelink");
          $(this).toggleClass("activelink");
          $list.slideToggle().toggleClass('hide');
          if ($list.hasClass('hide')) {
            $(this).removeClass("activelink");
            $innertab.removeClass("opendrop");
          }else{
            $innertab.addClass("opendrop");
          }
          if (!$('.dropdownwithdropdownitem.activelink').length) {
            $('.innertab').removeClass("opendrop");
          }
      } else {
        console.log("desktop hit");
          $(".dropdownwithdropdownitem").removeClass("activelink");
          $(this).addClass("activelink");
          $('.tab-content .list').addClass('hide'); 
          $('#'+tagid+'-desktop').removeClass('hide'); 
      }
  });

  $(window).resize(function () {
      if (!isMobile()) {
        console.log("testt");
        
          // $('.list').addClass('hide'); 
      }
  });

  const body = document.body;
  const toggleBtn = document.getElementById('toggleBtn');
  const navLinks = document.getElementById('nav-links');
  const line1 = document.getElementById('line-1');
  const line2 = document.getElementById('line-2');

  window.toggleNav = function() {
      const isExpanded = toggleBtn.getAttribute('aria-expanded') === 'true';
      toggleBtn.setAttribute('aria-expanded', !isExpanded);
      navLinks.classList.toggle('nav--active');
      line1.classList.toggle('forward');
      line2.classList.toggle('backward');
      document.body.classList.toggle('blurred', !isExpanded);
      $(".headercontent").toggleClass("menu-open");
  };
});
$(document).ready(function () {
  function isMobileView() {
      return window.innerWidth <= 992;
  }

  function mobileDropdownHandler() {
      if (!isMobileView()) return; 
      $('.dropdown .navbaritem').off('click').on('click', function (event) {
          event.preventDefault(); 
          let $dropdown = $(this).closest('.dropdown');
          $dropdown.toggleClass('active');
          $dropdown.find('.dropdown-menu').slideToggle(200);
          $('.dropdown').not($dropdown).removeClass('active').find('.dropdown-menu').slideUp(200);
          $(".dropdownwithdropdownitem").removeClass("activelink");
      });
      $(document).off('click').on('click', function (event) {
          if (!isMobileView()) return;
          if (!$(event.target).closest('.dropdown').length) {
              $('.dropdown').removeClass('active');
              $('.dropdown-menu').slideUp(200);
          }
      });
  }
  mobileDropdownHandler();
  $(window).resize(function () {
      mobileDropdownHandler();
  });
});
$(document).ready(function () {
  function isMobileheader() {
      return $(window).width() <= 576;
  }

  let $previousShowLi = $("li.show");

  $(".navmobitem").click(function (e) {
      if (!isMobileheader()) return;
      e.preventDefault();

      var $li = $(this).parent("li");
      var $dropdownMenu = $li.find(".dropdown-menu");
      var hasDropdown = $dropdownMenu.length > 0; 
      var wasActive = $li.hasClass("active");
      $(".toggleitem").removeClass("toggleactive");
      $("li").removeClass("active show");
      $(".dropdown-menu").slideUp(300);
      $(".navbar_mob").removeClass("open-dropdown"); 

      if (wasActive) {
          if ($previousShowLi.length) {
              $previousShowLi.addClass("show");
          }
      } else {
          $li.addClass("active");
          $dropdownMenu.slideDown(300);
          if (hasDropdown) {
              $(".navbar_mob").addClass("open-dropdown"); 
              if($(this).hasClass("toggleitem")){
                $(this).addClass("toggleactive");
              }else{
                $(".toggleitem").removeClass("toggleactive");
              }
          }
          
      }
  });

  $(document).click(function (e) {
      if (!isMobileheader()) return;

      if (!$(e.target).closest(".navmobitem, .dropdown-menu").length) {
          $("li").removeClass("active");
          $(".dropdown-menu").slideUp(300);
          $(".navbar_mob").removeClass("open-dropdown"); 
          $(".toggleitem").removeClass("toggleactive");
          if ($previousShowLi.length) {
              $previousShowLi.addClass("show");
          }
      }
  });

  $(window).resize(function () {
      if (!isMobileheader()) {
          $("li").removeClass("active");
          $(".dropdown-menu").hide();
          $(".navbar_mob").removeClass("open-dropdown");
          $(".toggleitem").removeClass("toggleactive");
      }
  });
});
$(document).ready(function () {
  $(".footer_navbar:not(.footer_navbarbottom) .footeritem h2").click(function () {
      var $parent = $(this).parent(".footeritem");
      var $ul = $parent.find("ul");
      $(".footeritem").removeClass("active");
      $parent.addClass("active");
  });
});
$('.bannerbrands').on("init", function () {
  $(this).css("opacity", "1"); 
}).slick({
  slidesToShow: 6,
  slidesToScroll: 2,
  infinite: true,
  dots: false,
  speed: 1000,
  arrows: false,
  autoplay: false,
  adaptiveHeight: true,
  responsive: [
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 3,
          centerPadding: '13%',
          variableWidth: false,
          centerMode: true,
        }
      }, {
          breakpoint: 768,
          settings: {
            centerMode: true,
            slidesToShow: 3,
            centerPadding: '12%',
            variableWidth: false,
          }
      },{
          breakpoint: 576,
          settings: {
            centerMode: true,
            slidesToShow: 3,
            centerPadding: '12%',
            variableWidth: false,
          }
        },
  ]
});

$(document).ready(function () {
  function expertiseSlickSlider() {
      if ($(window).width() < 576) { 
          
              $('.our_expertise .experitesmain').on("init", function () {
                $(this).css("opacity", "1"); 
              }).slick({
                  slidesToShow: 1,
                  slidesToScroll: 1,
                  infinite: false,
                  arrows: false,
                  dots: false,
              });
          
      } else {
          if ($('.our_expertise .experitesmain').hasClass('slick-initialized')) {
              $('.our_expertise .experitesmain').slick('unslick');
          }
      }
  }
  function productSlickSlider() {
    if ($(window).width() < 576) { 
            $('.our_product .productsmain').on("init", function () {
              $(this).css("opacity", "1"); 
            }).slick({
                slidesToShow: 2,
                slidesToScroll: 1,
                infinite: false,
                arrows: false,
                dots: false,
            });
        
    } else {
        if ($('.our_product .productsmain').hasClass('slick-initialized')) {
            $('.our_product .productsmain').slick('unslick');
        }
    }
}
  expertiseSlickSlider();
  productSlickSlider();
  $(window).resize(function () {
    expertiseSlickSlider();
    productSlickSlider();
  });
});
