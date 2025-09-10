$(document).ready(function () {

  $(window).on('load', function() {
    $('#preloader').fadeOut();
  });
  

  $('#themeToggleButton').click(function(){
    $('body').toggleClass('dark')
  })

  $('.toggleSidebar').click(function(){
    $('.aside').toggleClass('active')
  })
  
  /*------------------------------------
    Initialize dark-mode
--------------------------------------*/
  $('.link-dark-mode').click(function () {
    $('body').toggleClass('dark')
  })


  /*------------------------------------
    Initialize selectpicker
--------------------------------------*/
  $('.selectpicker').selectpicker();



  /*------------------------------------
    Initialize fileInput
--------------------------------------*/
  $('#fileInput').on('change', function (e) {
    const files = e.target.files;
    if (files.length > 0) {
      $('.upload-placeholder').hide();
      $.each(files, function (i, file) {
        if (file.type.startsWith('image/')) {
          const reader = new FileReader();
          reader.onload = function (event) {
            const imageUrl = event.target.result;
            const imgElement = `<img src="${imageUrl}" class="file-item" style="height: 50px; border-radius: 8px;" />`;
            $('.file-list').append(imgElement);
          };

          reader.readAsDataURL(file);
        }
      });
    }
    $(this).val('');
  });

});


/*------------------------------------
    Initialize Swiper brand
--------------------------------------*/
var swiperAds = new Swiper(".swiper-ads", {
  speed: 1500,
  spaceBetween: 20,
  centeredSlides: false,
  loop: false,
  autoplay: {
    delay: 4000,
    disableOnInteraction: false,
  },
});
document.addEventListener('DOMContentLoaded', () => {
  const prevButton = document.querySelector('.swiper-action-ads .swiper-prev');
  const nextButton = document.querySelector('.swiper-action-ads .swiper-next');

  if (prevButton && nextButton) {
    prevButton.addEventListener('click', () => {
      swiperAds.slidePrev();
    });

    nextButton.addEventListener('click', () => {
      swiperAds.slideNext();
    });
  }
});



/*------------------------------------
    Initialize Swiper fields
--------------------------------------*/
var swiperfields = new Swiper(".swiper-fields", {
  slidesPerView: 3.2,
  speed: 1500,
  loop: true,
  spaceBetween:10,
  autoplay: {
    delay: 4000,
    disableOnInteraction: false,
  },
  breakpoints: {
    0: {
      slidesPerView: 1.1,
    },
    567: {
      slidesPerView: 2.1,
    },
    1025: {
      slidesPerView: 3.2,
    },
  },
});



/*------------------------------------
    Initialize datetimepicker
--------------------------------------*/
document.addEventListener('DOMContentLoaded', function () {
  const pickers = document.querySelectorAll('.datetimepicker');

  pickers.forEach((picker) => {
    new tempusDominus.TempusDominus(picker, {
      useCurrent: false,
      localization: {
        locale: 'en',
        format: 'yyyy-MM-dd'
      },
      display: {
        components: {
          calendar: true,
          clock: false,
          hours: false,
          minutes: false,
          seconds: false
        }
      },
    });
  });
});
// 



// 