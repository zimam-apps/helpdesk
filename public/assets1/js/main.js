$(document).ready(function () {

  $(window).on('load', function() {
    $('#preloader').fadeOut();
  });

    $('.nav-submenu').click(function() {
        if ($(window).width() < 992) {
            $(this).closest('.nav-item').find('.submenu').slideToggle();
        }
    });



  $('.link-dark-mode').click(function () {
    $('body').toggleClass('dark')
  })

  $('.selectpicker').selectpicker();

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
var swiperBrand = new Swiper(".swiper-brand", {
  speed: 1500,
  spaceBetween: 20,
  centeredSlides: false,
  loop: false,
  autoplay: {
    delay: 4000,
    disableOnInteraction: false,
  },
  // pagination: {
  //   el: ".swiper-brand .swiper-pagination",
  //   clickable: true,
  // },
  breakpoints: {
    0: {
      slidesPerView: 2.3,
      spaceBetween: 20,
    },
    576: {
      slidesPerView: 3.3,
      spaceBetween: 20,
    },
    991: {
      slidesPerView: 4.3,
      spaceBetween: 20,
    },
    1025: {
      slidesPerView: 4.8,
      spaceBetween: 20,
    },
  },
});


/*------------------------------------
    Initialize Swiper courses
--------------------------------------*/
var swiperService = new Swiper(".swiper-service", {
  slidesPerView: 3.2,
  speed: 1500,
  spaceBetween: 18,
  loop: false,
  autoplay: {
    delay: 4000,
    disableOnInteraction: false,
  },
  // pagination: {
  //   el: ".swiper-courses .swiper-pagination",
  //   clickable: true,
  // },
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
    Initialize Swiper project
--------------------------------------*/
var swiperProject = new Swiper(".swiper-project", {
  slidesPerView: 1,
  speed: 1500,
  spaceBetween: 20,
  loop: false,
autoplay: {
    delay: 4000,
    disableOnInteraction: false,
  },
  // autoplay: {
  //   delay: 4000,
  //   disableOnInteraction: false,
  // },
});

document.addEventListener('DOMContentLoaded', () => {
  const prevButton = document.querySelector('.swiper-action-project .swiper-prev');
  const nextButton = document.querySelector('.swiper-action-project .swiper-next');

  if (prevButton && nextButton) {
    prevButton.addEventListener('click', () => {
      swiperProject.slidePrev();
    });

    nextButton.addEventListener('click', () => {
      swiperProject.slideNext();
    });
  }
});
/*------------------------------------
    Initialize Swiper testemonial
--------------------------------------*/
var swiperfields = new Swiper(".swiper-fields", {
  slidesPerView: 3.2,
  speed: 1500,
  loop: false,
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
    Initialize Swiper university
--------------------------------------*/
var swiperImages = new Swiper(".swiper-images", {
  slidesPerView: 1,
  speed: 1500,
  loop: false,
  nested: true,
  touchEventsTarget: 'wrapper',
// autoplay: {
//     delay: 4000,
//     disableOnInteraction: false,
//   },
  pagination: {
    el: ".swiper-images .swiper-pagination",
    clickable: true,
  },
});

/*------------------------------------
    Initialize Swiper users
--------------------------------------*/
var swiperUsers = new Swiper(".swiper-users", {
  slidesPerView: 1,
  speed: 1500,
  loop: false,
  spaceBetween:10,
  autoplay: {
    delay: 4000,
    disableOnInteraction: false,
  },
    pagination: {
    el: '.swiper-pagination-users',
    type: 'fraction',
  },
});
document.addEventListener('DOMContentLoaded', () => {
  const prevButton = document.querySelector('.swiper-action-users .swiper-prev');
  const nextButton = document.querySelector('.swiper-action-users .swiper-next');

  if (prevButton && nextButton) {
    prevButton.addEventListener('click', () => {
      swiperUsers.slidePrev();
    });

    nextButton.addEventListener('click', () => {
      swiperUsers.slideNext();
    });
  }
});


// datetimepickerYear
document.addEventListener('DOMContentLoaded', function () {
  const pickers = document.querySelectorAll('.datetimepickerYear');

  pickers.forEach((picker) => {
    new tempusDominus.TempusDominus(picker, {
      useCurrent: false,
      localization: {
        locale: 'en',
        format: 'yyyy',
      },
      display: {
        components: {
          calendar: true,
          clock: false,
          year: true,
          month: false,
          date: false,
          hours: false,
          minutes: false,
          seconds: false
        },
        viewMode: 'years',
      },
    });
  });
});


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

const cards = document.querySelectorAll('.card');
const container = document.getElementById('sliderContainer');

if (container) {
  // حط كل الكود اللي يعتمد على container جواه

  function scrollToCard(card) {
    setTimeout(() => {
      const containerRect = container.getBoundingClientRect();
      const cardRect = card.getBoundingClientRect();
      const cardCenter = cardRect.left + cardRect.width / 2;
      const containerCenter = containerRect.left + containerRect.width / 2;
      const delta = containerCenter - cardCenter;

      container.scrollBy({
        left: -delta,
        behavior: 'smooth'
      });
    }, 310);
  }

  cards.forEach(card => {
    card.addEventListener('click', () => {
      cards.forEach(c => c.classList.remove('active'));
      card.classList.add('active');
      scrollToCard(card);
    });
  });

  let isDragging = false;
  let startX;
  let scrollLeft;

  container.addEventListener('mousedown', e => {
    isDragging = true;
    startX = e.pageX;
    scrollLeft = container.scrollLeft;
  });

  container.addEventListener('touchstart', e => {
    isDragging = true;
    startX = e.touches[0].pageX;
    scrollLeft = container.scrollLeft;
  });

  container.addEventListener('mouseup', () => {
    isDragging = false;
    snapToClosestCard();
  });

  container.addEventListener('touchend', () => {
    isDragging = false;
    snapToClosestCard();
  });

  container.addEventListener('mouseleave', () => {
    isDragging = false;
  });

  container.addEventListener('mousemove', e => {
    if (!isDragging) return;
    const x = e.pageX;
    const walk = (x - startX) * -1;
    container.scrollLeft = scrollLeft + walk;
  });

  container.addEventListener('touchmove', e => {
    if (!isDragging) return;
    const x = e.touches[0].pageX;
    const walk = (x - startX) * -1;
    container.scrollLeft = scrollLeft + walk;
  });

  function snapToClosestCard() {
    let minDist = Infinity;
    let closestCard = null;
    const containerCenter = container.getBoundingClientRect().left + container.offsetWidth / 2;

    cards.forEach(card => {
      const rect = card.getBoundingClientRect();
      const cardCenter = rect.left + rect.width / 2;
      const dist = Math.abs(containerCenter - cardCenter);
      if (dist < minDist) {
        minDist = dist;
        closestCard = card;
      }
    });

    if (closestCard) {
      cards.forEach(c => c.classList.remove('active'));
      closestCard.classList.add('active');
      scrollToCard(closestCard);
    }
  }
}
