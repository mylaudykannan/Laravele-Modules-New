<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- dashboard theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <!-- CSS Files -->
    {{-- <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" /> --}}
    <link rel="stylesheet" href="{{asset('assets')}}/css/bootstrap.min.css">
    {{-- <link rel="stylesheet" href="{{asset('frontend')}}/css/style.css"> --}}
    <link href="{{ asset('assets/css/light-bootstrap-dashboard.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    
    
    <link href="{{ asset('css/admincustom.css') }}" rel="stylesheet">


    @stack('stylesheets') 
</head>
<body class="@yield('body_class')">
    <input type="hidden" id="rootfolder" value="{{env('ROOTFOLDER')}}">
    <div class="wrapper">

        @include('admin.inc.sidebar')


        <!-- Main content -->
        <div class="main-panel">
           {{--   @include('admin.inc.topnav') --}}
            <div class="content">
                @yield('content')
            </div>
            {{-- <footer class="footer">
                <div class="container-fluid">
                    <nav>
                        <ul class="footer-menu">
                            <li>
                                <a href="#">
                                    Home
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Company
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Portfolio
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Blog
                                </a>
                            </li>
                        </ul>
                        <p class="copyright text-center">
                            ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script>
                            <a href="javascript:void(0)">UAQFTZ</a>
                        </p>
                    </nav>
                </div>
            </footer> --}}
        </div>

        <!-- mobile footer -->
        <div class="mobile-footer d-flex d-md-none">
            <div class="nav-links items d-flex align-items-start w-100 justify-content-around">
                <a class="nav-link" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M19 8.71l-5.333 -4.148a2.666 2.666 0 0 0 -3.274 0l-5.334 4.148a2.665 2.665 0 0 0 -1.029 2.105v7.2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-7.2c0 -.823 -.38 -1.6 -1.03 -2.105"></path><path d="M16 15c-2.21 1.333 -5.792 1.333 -8 0"></path>
                    </svg>
                </a>
                <a class="nav-link me-2 active" href="#">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 3L20 7.5V16.5L12 21L4 16.5V7.5L12 3Z" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M12 12L20 7.5" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M12 12V21" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M12 12L4 7.5" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M16 5.25L8 9.75" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
                <a class="nav-link" href="#">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.6667 13C16.378 13 15.3333 11.9926 15.3333 10.75C15.3333 9.50736 16.378 8.5 17.6667 8.5C18.9553 8.5 20 9.50736 20 10.75C20 11.9926 18.9553 13 17.6667 13ZM9.26667 10.3C7.46253 10.3 6 8.8897 6 7.15C6 5.4103 7.46253 4 9.26667 4C11.0708 4 12.5333 5.4103 12.5333 7.15C12.5333 8.8897 11.0708 10.3 9.26667 10.3Z" stroke="currentcolor" stroke-width="2"></path>
                        <path d="M23 19.9922L22.9989 19.9927M19.7163 20C19.6356 18.1991 19.1211 16.5162 18.2797 15.0639C19.4423 15.2101 20.4634 15.6063 21.2492 16.2444C22.2296 17.0403 22.8835 18.2434 22.9761 19.9287C22.9772 19.9467 22.9773 19.9696 22.9745 19.9921L22.9737 19.9979C22.9639 19.9991 22.9511 20 22.9346 20H19.7163ZM1.00517 19.6947C1.00321 19.691 1.0015 19.6876 1 19.6845C1.18555 17.4658 2.13261 15.8164 3.54475 14.7101C4.97917 13.5863 6.92478 13 9.10372 13C11.3218 13 13.2971 13.5566 14.7395 14.6632C16.1676 15.7588 17.1098 17.4197 17.2416 19.7298C17.2434 19.7593 17.2437 19.7996 17.2385 19.8416C17.2329 19.8846 17.2232 19.9154 17.2132 19.9347C17.2055 19.9494 17.1981 19.9579 17.1837 19.9663C17.1671 19.9761 17.1159 20 17.0007 20H16.98H16.9591H16.9383H16.9174H16.8965H16.8755H16.8545H16.8335H16.8124H16.7912H16.7701H16.7489H16.7277H16.7064H16.6851H16.6637H16.6422H16.6208H16.5993H16.5778H16.5563H16.5347H16.513H16.4914H16.4697H16.4479H16.4261H16.4043H16.3824H16.3605H16.3385H16.3166H16.2945H16.2725H16.2503H16.2282H16.206H16.1838H16.1616H16.1392H16.1168H16.0945H16.0721H16.0496H16.0271H16.0045H15.982H15.9593H15.9367H15.914H15.8913H15.8684H15.8457H15.8228H15.7999H15.777H15.754H15.731H15.7079H15.6849H15.6617H15.6385H15.6153H15.5921H15.5688H15.5454H15.5221H15.4987H15.4752H15.4518H15.4282H15.4047H15.3811H15.3574H15.3337H15.31H15.2863H15.2625H15.2386H15.2147H15.1908H15.1668H15.1428H15.1187H15.0947H15.0706H15.0464H15.0222H14.998H14.9738H14.9494H14.925H14.9006H14.8762H14.8517H14.8273H14.8027H14.7781H14.7535H14.7288H14.7041H14.6793H14.6546H14.6297H14.6049H14.5799H14.555H14.53H14.505H14.48H14.4548H14.4297H14.4046H14.3793H14.354H14.3288H14.3034H14.278H14.2526H14.2272H14.2017H14.1762H14.1505H14.1249H14.0992H14.0736H14.0478H14.0221H13.9963H13.9704H13.9445H13.9186H13.8926H13.8666H13.8405H13.8144H13.7883H13.7622H13.736H13.7097H13.6834H13.6571H13.6308H13.6044H13.5779H13.5514H13.5249H13.4984H13.4717H13.4451H13.4184H13.3917H13.365H13.3381H13.3114H13.2844H13.2576H13.2306H13.2036H13.1765H13.1495H13.1223H13.0952H13.0681H13.0408H13.0136H12.9863H12.9589H12.9315H12.9041H12.8766H12.8491H12.8216H12.794H12.7664H12.7386H12.711H12.6832H12.6555H12.6277H12.5998H12.5719H12.544H12.516H12.488H12.4599H12.4318H12.4037H12.3755H12.3473H12.319H12.2907H12.2624H12.2341H12.2056H12.1772H12.1487H12.1201H12.0916H12.063H12.0343H12.0057H11.9769H11.9481H11.9194H11.8905H11.8616H11.8327H11.8037H11.7748H11.7457H11.7166H11.6875H11.6584H11.6291H11.5999H11.5706H11.5413H11.512H11.4825H11.4531H11.4237H11.3941H11.3646H11.335H11.3053H11.2756H11.246H11.2162H11.1865H11.1565H11.1267H11.0968H11.0669H11.0369H11.0069H10.9768H10.9467H10.9166H10.8864H10.8561H10.8259H10.7956H10.7652H10.7348H10.7044H10.6739H10.6434H10.613H10.5824H10.5517H10.521H10.4904H10.4597H10.4289H10.398H10.3672H10.3363H10.3053H10.2744H10.2434H10.2123H10.1813H10.1501H10.119H10.0877H10.0565H10.0252H9.99388H9.96252H9.93112H9.89968H9.86819H9.83667H9.8051H9.7735H9.74186H9.71019H9.67846H9.6467H9.61491H9.58306H9.55118H9.51927H9.48732H9.45532H9.42329H9.39121H9.35909H9.32694H9.29475H9.26251H9.23025H9.19794H9.16558H9.13319H9.10077H9.06829H9.03579H9.00324H8.97065H8.93803H8.90536H8.87266H8.83992H8.80713H8.77431H8.74145H8.70854H8.6756H8.64262H8.6096H8.57655H8.54345H8.51031H8.47713H8.44392H8.41066H8.37737H8.34403H8.31065H8.27724H8.24379H8.2103H8.17676H8.14319H8.10958H8.07593H8.04224H8.00851H7.97475H7.94094H7.9071H7.87321H7.83928H7.80532H7.77131H7.73726H7.70319H7.66906H7.6349H7.6007H7.56646H7.53218H7.49786H7.4635H7.4291H7.39466H7.36019H7.32567H7.29111H7.25652H7.22188H7.1872H7.15249H7.11774H7.08295H7.04811H7.01324H6.97833H6.94338H6.9084H6.87336H6.8383H6.80319H6.76805H6.73286H6.69763H6.66237H6.62706H6.59172H6.55634H6.52091H6.48545H6.44995H6.41441H6.37882H6.34321H6.30755H6.27185H6.23612H6.20034H6.16452H6.12866H6.09277H6.05683H6.02086H5.98485H5.94879H5.9127H5.87658H5.8404H5.80419H5.76794H5.73165H5.69532H5.65895H5.62255H5.5861H5.54961H5.51309H5.47653H5.43992H5.40327H5.36659H5.32987H5.2931H5.25631H5.21947H5.18259H5.14566H5.10871H5.07171H5.03467H4.99759H4.96048H4.92332H4.88612H4.84889H4.81162H4.7743H4.73696H4.69956H4.66213H4.62466H4.58714H4.5496H4.51201H4.47439H4.43671H4.399H4.36126H4.32348H4.28565H4.24779H4.20988H4.17194H4.13395H4.09593H4.05787H4.01977H3.98163H3.94345H3.90523H3.86698H3.82867H3.79033H3.75196H3.71354H3.67509H3.63659H3.59806H3.55948H3.52087H3.48222H3.44353H3.4048H3.36603H3.32722H3.28837H3.24947H3.21055H3.17158H3.13258H3.09353H3.05444H3.01532H2.97615H2.93694H2.8977H2.85841H2.8191H2.77973H2.74033H2.70089H2.66141H2.6219H2.58233H2.54274H2.5031H2.46343H2.42371H2.38396H2.34416H2.30432H2.26445H2.22454H2.18459H2.14459H2.10456H2.06449H2.02438H1.98423H1.94404H1.90381H1.86355H1.82324H1.78289H1.7425H1.70208H1.66162H1.62112H1.58057H1.53999H1.49936H1.45871H1.418H1.37726H1.33648H1.29567H1.27311C1.27311 20 1.26968 19.9977 1.26782 19.9963C1.22807 19.9684 1.17529 19.921 1.12215 19.8601C1.06903 19.7992 1.02851 19.7395 1.00517 19.6947Z" stroke="currentcolor" stroke-width="1.5"></path>
                    </svg>
                </a>       
                <a class="nav-link  me-2" href="#">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5H7C6.46957 5 5.96086 5.21071 5.58579 5.58579C5.21071 5.96086 5 6.46957 5 7V19C5 19.5304 5.21071 20.0391 5.58579 20.4142C5.96086 20.7893 6.46957 21 7 21H17C17.5304 21 18.0391 20.7893 18.4142 20.4142C18.7893 20.0391 19 19.5304 19 19V7C19 6.46957 18.7893 5.96086 18.4142 5.58579C18.0391 5.21071 17.5304 5 17 5H15" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M13 3H11C9.89543 3 9 3.89543 9 5C9 6.10457 9.89543 7 11 7H13C14.1046 7 15 6.10457 15 5C15 3.89543 14.1046 3 13 3Z" stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M9 12H9.01" stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M13 12H15" stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M9 16H9.01" stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M13 16H15" stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
            </div>
        </div>

    </div>
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}" type="text/javascript"></script>
    {{--  <script type="text/javascript" src="{{ asset('backend/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/js/js.cookie.js') }}"></script>
    <script src="{{ asset('backend/js/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('backend/js/jquery-scrollLock.min.js') }}"></script>
    <script src="{{ asset('backend/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('backend/js/dash.js') }}"></script> --}}

    <!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
    <script src="{{ asset('assets/js/plugins/bootstrap-switch.js') }}"></script>
    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
    <!--  Chartist Plugin  -->
    <script src="{{ asset('assets/js/plugins/chartist.min.js') }}"></script>
    <!--  Notifications Plugin    -->
    <script src="{{ asset('assets/js/plugins/bootstrap-notify.js') }}"></script>
    <!-- Control Center for Light Bootstrap Dashboard: scripts for the example pages etc -->
    <script src="{{ asset('assets/js/light-bootstrap-dashboard.js') }}" type="text/javascript"></script>



    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    
    <script src="{{ asset('js/admincustom.js') }}" defer></script>

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
          var options = {
            damping: '0.5'
          }
          Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    {{-- <script src="{{ asset('assets/js/material-dashboard.min.js') }}"></script> --}}

    <script>
        var root = $('#rootfolder').val();
        function addflatpicker()
        {
            $('.flatpicker').each(function(i, obj) {
                var elm = $(this);
                var config = elm.attr('flat-config');
                if (!(typeof config !== typeof undefined && config !== false)) {
                    config = 'default';
                }
                $.getJSON(root+'/flatpicker.json', function(jd) {
                    flatoptions = jd;
                    flatpickr(elm, flatoptions[config]);
                    console.log(flatoptions[config]);
                });
                /* var option = '<?php echo json_encode(config("flatpicker.default"));?>';
                option = $.parseJSON(option);   */      
            });
        }
        addflatpicker();
        // setTimeout(function() {$('#headertopcontent_ifr').addClass('mydiv');}, 2000);
        
    </script>

    @stack('contentpush')
    @stack('scripts')    
</body>
</html>
