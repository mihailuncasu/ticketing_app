@extends('layouts.app')

@section('content')
    <header class="masthead text-white text-center"
            style="background: url('{{asset('img/welcome/bg-masthead.jpg')}}')no-repeat center center;background-size: cover;background-image: url(&quot;{{asset('img/welcome/bg-masthead.jpg')}}&quot;);">
        <div class="shadow overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-xl-9 mx-auto">
                    <h1 class="mb-5"><br><strong>ServiceNow Plus, the tool for all your ITSM needs</strong><br></h1>
                </div>
                <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
                    <form method="GET" action="{{ route('register') }}">
                        <div class="form-row">
                            <div class="col-12 col-md-9 mb-2 mb-md-0"><input class="form-control form-control-lg"
                                                                             type="email"
                                                                             id="email"
                                                                             name="email"
                                                                             placeholder="Enter your email..."></div>
                            <div class="col-12 col-md-3">
                                <button class="btn btn-primary btn-block btn-lg" type="submit">{{__('Sign up')}}!</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </header>
    <section class="features-icons bg-light text-center" style="background-color: transparent;">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="mx-auto features-icons-item mb-5 mb-lg-0 mb-lg-3">
                        <div class="d-flex features-icons-icon"><i class="icon-note m-auto text-primary"
                                                                   data-bs-hover-animate="pulse"></i></div>
                        <h3><strong>Fully customization</strong><br></h3>
                        <p class="lead mb-0">Design your own process flow using our simple interface. Now it's simple
                            than ever!<br></p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mx-auto features-icons-item mb-5 mb-lg-0 mb-lg-3">
                        <div class="d-flex features-icons-icon"><i class="la la-sitemap m-auto text-primary"
                                                                   data-bs-hover-animate="pulse"></i></div>
                        <h3>Smart automation</h3>
                        <p class="lead mb-0">Resolve tickets faster using an automatic ticket routing mechanism and
                            business rules.<br></p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mx-auto features-icons-item mb-5 mb-lg-0 mb-lg-3">
                        <div class="d-flex features-icons-icon"><i class="icon-check m-auto text-primary"
                                                                   data-bs-hover-animate="pulse"></i></div>
                        <h3>Easy to Use</h3>
                        <p class="lead mb-0">It's easy to get started and grow without pressure. Outstanding
                            support!<br></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="showcase">
        <div class="container-fluid p-0">
            <div class="row no-gutters" data-aos="fade">
                <div class="col-lg-6 order-lg-2 text-white showcase-img"
                     style="background-image: url(&quot;{{asset('img/welcome/bg-showcase-1.jpg')}}&quot;);"><span></span></div>
                <div class="col-lg-6 my-auto order-lg-1 showcase-text">
                    <h2>Who We Are<br></h2>
                    <p class="lead mb-0" style="padding-top: 5px;">ServiceNow believes in the power of technology to
                        reduce the complexity in our jobs and make work, work better for people.<br></p>
                </div>
            </div>
            <div class="row no-gutters" data-aos="fade">
                <div class="col-lg-6 text-white showcase-img"
                     style="background-image: url(&quot;{{asset('img/welcome/bg-showcase-2.jpg')}}&quot;);filter: contrast(126%) grayscale(0%) hue-rotate(27deg);">
                    <span></span></div>
                <div class="col-lg-6 my-auto order-lg-1 showcase-text">
                    <h2>What We Do<br></h2>
                    <p class="lead mb-0" style="padding-top: 5px;">We transform old, manual ways of working into modern
                        digital workflows. Employees and customers get what they need, when they need it—fast, simple,
                        easy.<br></p>
                </div>
            </div>
            <div class="row no-gutters" data-aos="fade">
                <div class="col-lg-6 order-lg-2 text-white showcase-img"
                     style="background-image: url(&quot;{{asset('img/welcome/bg-showcase-4.jpg')}}&quot;);"><span></span></div>
                <div class="col-lg-6 my-auto order-lg-1 showcase-text">
                    <h2><strong>Consolidate IT services</strong><br></h2>
                    <p class="lead mb-0" style="padding-top: 5px;">Deliver services that amaze your users, increase
                        productivity, and achieve new insights by consolidating to the most
                        innovative&nbsp;<strong><span style="text-decoration: underline;">ITSM</span></strong>&nbsp;solution.<br>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- Start: Simple Slider -->
    <div class="text-center simple-slider" data-aos="zoom-in" style="margin-top: 3%;">
        <h2 style="margin: 0 0 0 0;/*margin-top: 3%;*/">What people are saying...</h2>
        <!-- Start: Slideshow -->
        <div class="swiper-container">
            <!-- Start: Slide Wrapper -->
            <div class="swiper-wrapper">
                <!-- Start: Slide -->
                <div class="swiper-slide" style="max-height: 450px;">
                    <div class="container text-center">
                        <div class="row align-items-baseline" style="padding: 5px;">
                            <div class="col-lg-4">
                                <div class="mx-auto testimonial-item mb-5 mb-lg-0"><img
                                            class="rounded-circle img-fluid mb-3" src="{{asset('img/welcome/testimonials-2.jpg')}}"
                                            style="height: 200px;">
                                    <h5>Experian</h5>
                                    <p class="font-weight-light mb-0">"Through ServiceNow's seamless integration with
                                        ITSM, we are on the path..."<br></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mx-auto testimonial-item mb-5 mb-lg-0"><img
                                            class="rounded-circle img-fluid mb-3" src="{{asset('img/welcome/testimonials-3.jpg')}}"
                                            style="height: 200px;">
                                    <h5>Malaysia Airlines</h5>
                                    <p class="font-weight-light mb-0">"Malaysia Airlines unifies the employee experience
                                        with ServiceNow!"<br></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mx-auto testimonial-item mb-5 mb-lg-0"><img
                                            class="rounded-circle img-fluid flex-grow-1 flex-shrink-1 mb-3"
                                            src="{{asset('img/welcome/testimonials-1.jpg')}}" style="height: 200px;">
                                    <h5>Siemens Healthineers</h5>
                                    <p class="font-weight-light mb-0">"ServiceNow has delivered a platform to free up
                                        time and remove frustration...!"<br></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End: Slide -->
            </div>
            <!-- End: Slide Wrapper -->
            <!-- Start: Pagination -->
            <div class="swiper-pagination"></div>
            <!-- End: Pagination -->
            <!-- Start: Previous -->
            <div class="swiper-button-prev"></div>
            <!-- End: Previous -->
            <!-- Start: Next -->
            <div class="swiper-button-next"></div>
            <!-- End: Next -->
        </div>
        <!-- End: Slideshow -->
    </div>
    <!-- End: Simple Slider -->
    <section data-aos="fade-up" class="call-to-action text-white text-center"
             style="background:url(&quot;{{asset('img/welcome/bg-masthead.jpg')}}&quot;) no-repeat center center;background-size:cover;">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-xl-9 mx-auto">
                    <h2 class="mb-4">Ready to get started? Sign up now!</h2>
                </div>
                <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
                    <form method="GET" action="{{ route('register') }}">
                        <div class="form-row">
                            <div class="col-12 col-md-9 mb-2 mb-md-0"><input class="form-control form-control-lg"
                                                                             type="email"
                                                                             name="email"
                                                                             placeholder="Enter your email..."></div>
                            <div class="col-12 col-md-3">
                                <button class="btn btn-primary btn-block btn-lg" type="submit">{{__('Sign up')}}!</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection