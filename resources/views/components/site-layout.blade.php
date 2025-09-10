@props(['internal' => false])
<!DOCTYPE html>
<html dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>رفع تذكرة دعم تقني - زمام القوة</title>
    <meta property="og:type" content="" />
    <meta property="og:title" content="" />
    <meta property="og:description" content=" " />
    <meta property="og:image" content="" />
    <meta property="og:image:width" content="" />
    <meta property="og:image:height" content="" />
    <meta property="og:url" content="" />
    <meta property="og:site_name" content=" " />
    <meta property="og:ttl" content="" />
    <meta name="twitter:course" content="" />
    <meta name="twitter:domain" content="" />
    <meta name="twitter:site" content="" />
    <meta name="twitter:creator" content="" />
    <meta name="twitter:image:src" content="" />
    <meta name="twitter:description" content="" />
    <meta name="twitter:title" content=" " />
    <meta name="twitter:url" content="" />
    <meta name="description" content="  " />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <meta name="copyright" content=" " />
    <link rel="stylesheet" href="{{asset('css/assets1/css/tempus-dominus.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/assets1/css/fontawesome.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/assets1/css/bootstrap-select.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/assets1/css/swiper.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/assets1/css/bootstrap.rtl.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/assets1/css/toastr.min.css')}}"/>
    <!-- <link rel="stylesheet" type="text/css" href="assets1/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="{{asset('css/assets1/css/main.css')}}" />
    @yield('styles')
  </head>
  <body>
    <!-- begin:: Page -->
    <div class="page">

      <div id="preloader" class="preloader">
        <img width="500" height="600" loading="lazy" class="preloader__img" src="{{asset('css/assets1/images/loader.gif')}}" alt="">
      </div>

      <!-- begin:: Header -->

      <!-- end:: Header -->
      <main>
        {{$slot}}
      </main>
      <!-- start:: footer -->

      <!-- end:: footer -->
    </div>
    <!-- end:: Page -->
    <script src="{{asset('css/assets1/js/query.min.js')}}"></script>
    <script src="{{asset('css/assets1/js/popper.min.js')}}"></script>
    <script src="{{asset('css/assets1/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('css/assets1/js/swiper.min.js')}}"></script>
    <script src="{{asset('css/assets1/js/toastr.min.js')}}"></script>
    <script src="{{asset('css/assets1/js/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('css/assets1/js/tempus-dominus.min.js')}}"></script>
    <script src="{{asset('css/assets1/js/main.js?v=1')}}"></script>
    @yield('scripts')

    <script>
      @if(session('success'))
          toastr.success("{{session('success')}}");
      @endif

      @if(session('error'))
          toastr.error("{{ session('error') }}");
      @endif

      @if(session('info'))
          toastr.info("{{ session('info') }}");
      @endif

      @if(session('warning'))
          toastr.warning("{{ session('warning') }}");
      @endif
    </script>

  </body>
</html>
