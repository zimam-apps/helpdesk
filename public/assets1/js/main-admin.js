$(document).ready(function () {

  $(window).on('load', function() {
    $('#preloader').fadeOut();
  });

  /*------------------------------------
      Initialize selectpicker
  --------------------------------------*/
  $('.selectpicker').selectpicker();


  $('#themeToggleButton').click(function(){
    $('body').toggleClass('dark')
  })

  $('.toggleSidebar').click(function(){
    $('.aside').toggleClass('active')
  });


  $('.main-menu__link.menu-toggle').click(function(){
    $(this).closest('.main-menu__item').toggleClass('active')
    $(this).closest('.main-menu__item').siblings('.main-menu__item').removeClass('active')
    $(this).closest('.main-menu__item').siblings('.main-menu__item').find('.menu-submenu').slideUp()
    $(this).closest('.main-menu__item').find('.menu-submenu').slideToggle()
  });
  

  $('#myTable').DataTable({
    language: {
      url: '//cdn.datatables.net/plug-ins/1.10.16/i18n/Arabic.json'
    }
  });
});