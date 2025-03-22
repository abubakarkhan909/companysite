$(document).ready(function () {
  function handleHoverEffect() {
      if (window.innerWidth > 992) { // Only run on screens larger than 991px
          $('.dropdown').hover(
              function () {
                  $('.headercontent').addClass('menu-open');
              },
              function () {
                  setTimeout(() => {
                      if (!$('.dropdown:hover, .dropdown-menu:hover').length) {
                          $('.headercontent').removeClass('menu-open');
                      }
                  }, 200);
              }
          );

          $('.dropdown-menu').mouseleave(function () {
              setTimeout(() => {
                  if (!$('.dropdown:hover, .dropdown-menu:hover').length) {
                      $('.headercontent').removeClass('menu-open');
                  }
              }, 200);
          });
      } else {
          // Remove hover effects for mobile
          $('.dropdown').off('mouseenter mouseleave');
          $('.dropdown-menu').off('mouseleave');
          $('.headercontent').removeClass('menu-open');
      }
  }
  handleHoverEffect();

  $(window).resize(handleHoverEffect);
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
          $('.list').hide().addClass('hide'); 
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
      var hasDropdown = $dropdownMenu.length > 0; // Check if dropdown exists
      var wasActive = $li.hasClass("active");

      $("li").removeClass("active show");
      $(".dropdown-menu").slideUp(300);
      $(".navbar_mob").removeClass("open-dropdown"); // Always remove class first

      if (wasActive) {
          if ($previousShowLi.length) {
              $previousShowLi.addClass("show");
          }
      } else {
          $li.addClass("active");
          $dropdownMenu.slideDown(300);
          if (hasDropdown) {
              $(".navbar_mob").addClass("open-dropdown"); // Only add if dropdown exists
          }
      }
  });

  $(document).click(function (e) {
      if (!isMobileheader()) return;

      if (!$(e.target).closest(".navmobitem, .dropdown-menu").length) {
          $("li").removeClass("active");
          $(".dropdown-menu").slideUp(300);
          $(".navbar_mob").removeClass("open-dropdown"); // Remove class when clicking outside

          if ($previousShowLi.length) {
              $previousShowLi.addClass("show");
          }
      }
  });

  $(window).resize(function () {
      if (!isMobileheader()) {
          $("li").removeClass("active");
          $(".dropdown-menu").hide();
          $(".navbar_mob").removeClass("open-dropdown"); // Remove class on resize
      }
  });
});



