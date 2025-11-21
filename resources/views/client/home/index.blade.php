@extends('layouts.client')


@section('content')
<section class="position-relative py-5">

    <!-- Gradient BG -->
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-primary opacity-10"></div>

    <!-- Content -->
    <div class="container position-relative zindex-2 py-lg-4">
      <div class="row">
        <div class="col-lg-5 d-flex flex-column pt-lg-4 pt-xl-5">
          <h5 class="my-2">Welcome!</h5>
          <h1 class="display-3 mb-4">Learn <span class="text-primary">IT Online</span> with No Limits</h1>
          <p class="fs-lg mb-5">Enjoy our great selection of IT courses. Choose from more than 25K online video courses and become an IT expert now!</p>

          <!-- Desktop form -->
          <form class="d-none d-sm-flex mb-5">
            <div class="input-group d-block d-sm-flex input-group-lg me-3">
              <input type="text" class="form-control w-50" placeholder="Search courses...">
              <select class="form-select w-50">
                <option value="" selected disabled>Categories</option>
                <option value="Web Development">Web Development</option>
                <option value="Mobile Development">Mobile Development</option>
                <option value="Programming">Programming</option>
                <option value="Game Development">Game Development</option>
                <option value="Software Testing">Software Testing</option>
                <option value="Software Engineering">Software Engineering</option>
                <option value="Network & Security">Network &amp; Security</option>
              </select>
            </div>
            <button type="submit" class="btn btn-icon btn-primary btn-lg">
              <i class="bx bx-search"></i>
            </button>
          </form>
          
          <!-- Mobile form -->
          <form class="d-sm-none mb-5">
            <input type="text" class="form-control form-control-lg mb-2" placeholder="Search courses...">
            <select class="form-select form-select-lg mb-2">
              <option value="" selected disabled>Categories</option>
              <option value="Web Development">Web Development</option>
              <option value="Mobile Development">Mobile Development</option>
              <option value="Programming">Programming</option>
              <option value="Game Development">Game Development</option>
              <option value="Software Testing">Software Testing</option>
              <option value="Software Engineering">Software Engineering</option>
              <option value="Network & Security">Network &amp; Security</option>
            </select>
            <button type="submit" class="btn btn-icon btn-primary btn-lg w-100 d-sm-none">
              <i class="bx bx-search"></i>
            </button>
          </form>
          <div class="d-flex align-items-center mt-auto mb-3 mb-lg-0 pb-4 pb-lg-0 pb-xl-5">
            <div class="d-flex me-3">
              <div class="d-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 52px; height: 52px;">
                <img src="assets/img/avatar/08.jpg" class="rounded-circle" width="48" alt="Avatar">
              </div>
              <div class="d-flex align-items-center justify-content-center bg-light rounded-circle ms-n3" style="width: 52px; height: 52px;">
                <img src="assets/img/avatar/15.jpg" class="rounded-circle" width="48" alt="Avatar">
              </div>
              <div class="d-flex align-items-center justify-content-center bg-light rounded-circle ms-n3" style="width: 52px; height: 52px;">
                <img src="assets/img/avatar/16.jpg" class="rounded-circle" width="48" alt="Avatar">
              </div>
            </div>
            <span class="fs-sm"><span class="text-primary fw-semibold">10K+</span> students are already with us</span>
          </div>
        </div>
        <div class="col-lg-7">

          <!-- Parallax gfx -->
          <div class="parallax mx-auto me-lg-0" style="max-width: 648px;">
            <div class="parallax-layer" data-depth="0.1">
              <img src="assets/img/landing/online-courses/hero/layer01.png" alt="Layer">
            </div>
            <div class="parallax-layer" data-depth="0.13">
              <img src="assets/img/landing/online-courses/hero/layer02.png" alt="Layer">
            </div>
            <div class="parallax-layer zindex-5" data-depth="-0.12">
              <img src="assets/img/landing/online-courses/hero/layer03.png" alt="Layer">
            </div>
            <div class="parallax-layer zindex-3" data-depth="0.27">
              <img src="assets/img/landing/online-courses/hero/layer04.png" alt="Layer">
            </div>
            <div class="parallax-layer zindex-1" data-depth="-0.18">
              <img src="assets/img/landing/online-courses/hero/layer05.png" alt="Layer">
            </div>
            <div class="parallax-layer zindex-1" data-depth="0.1">
              <img src="assets/img/landing/online-courses/hero/layer06.png" alt="Layer">
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
