@extends('layouts.frontend')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/landing/index.css') }}">
@endsection
@section('content')
<!-- promosi -->
<div class="promosi pt-3 pb-3">
    <div class="container text-center blink_me">
        PEMADAMAN LISTRIK AKAN DILAKUKAN PADA TANGGAL 31 JANUARI 2023!! PADA PUKUL 13.00
    </div>
</div>
<!-- end promosi -->
<!-- carausel -->
<div id="carouselId" class="carousel slide" data-bs-ride="carousel">
    <ol class="carousel-indicators">
        <li data-bs-target="#carouselId" data-bs-slide-to="0" class="active"></li>
        <li data-bs-target="#carouselId" data-bs-slide-to="1"></li>
        <li data-bs-target="#carouselId" data-bs-slide-to="2"></li>
        <li data-bs-target="#carouselId" data-bs-slide-to="3"></li>
    </ol>
    <div class="carousel-inner" role="listbox">
        <div class="carousel-item active">
            <img src="{{asset('assets/img/photo1.png')}}" class="img-fluid w-100" id="gambar_slider" alt="First slide">
        </div>
        <div class="carousel-item">
            <img src="{{asset('assets/img/photo2.png')}}" class="img-fluid w-100" id="gambar_slider" alt="Second slide">
        </div>
        <div class="carousel-item">
            <img src="{{asset('assets/img/gmrom.png')}}" class="img-fluid w-100" id="gambar_slider" alt="Third slide">
        </div>
        <div class="carousel-item">
            <img src="{{asset('assets/img/livi.jpg')}}" class="img-fluid w-100" id="gambar_slider" alt="Forth slide">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselId" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
<div class="container-fluid">
    <!-- Section About Start -->
    <section class="page-section py-5" id="about" style="height: 100vh">
        <div class="content my-5 ">
            <div class="row mb-sm-3">
                <div class="col-sm">
                    <h2 class="text-center my-sm-3 section-heading text-uppercase font-weight-bold">About</h2>
                </div>
            </div>
            <div class="row rows justify-content-center align-items-center">
                <div class="col-sm-6 overflow-hidden box text-center">
                    <img src="{{asset ('/assets/img/undraw_electricity_k2ft.svg')}}" alt="" height="300px">
                </div>
                <div class="col-sm-6">
                    <center>
                        <h3 class="text-center my-3">Visi</h3>
                        <p style="width: 500px">Selalu menjadi yang terbaik untuk memberikan pelayanan bagi semua orang, menjaga keamanan dan kenyamanan dalam bertransaksi, juga membuat pelanggan senang.</p>
                    </center>
                </div>
            </div>
            <div class="row rows justify-content-center align-items-center">
                <div class="col-sm-6">
                    <div class="d-block px-5">
                        <h3 class="text-center mb-sm-3 mt-sm-5">Misi</h3>
                        <ul class="px-5 mx-5">
                            <li>
                                <p align="justify">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Illum illo quos, rerum, culpa enim doloremque impedit labore amet tenetur necessitatibus, totam corporis quam! Vero repellat, sed nostrum et ut nisi?</p>
                            </li>
                            <li>
                                <p align="justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur minus laborum ullam neque officiis, dolores voluptatem alias corporis, facilis fugiat temporibus recusandae assumenda. Laboriosam molestias minus facere, iste quam vitae..</p>
                            </li>
                            <li>
                                <p align="justify">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Repellat, fugiat inventore. Quisquam eos quasi totam, distinctio cumque quaerat perferendis consectetur fuga, maxime, dignissimos neque. Eum est et voluptatibus voluptate maxime..</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6 overflow-hidden box text-center">
                    <img src="{{asset ('/assets/img/undraw_solution_mindset_re_57bf.svg')}}" alt="" height="300px">
                </div>
            </div>
        </div>
    </section>
    <!-- Section About End -->
    <!-- Section Sevice Start -->
    <section class="page-section py-5 d-flex align-items-center justify-content-center" id="service" style="height: 100vh">
        <div class="container my-5">
            <h2 class="section-heading text-uppercase font-weight-bold text-center">Service</h2>
            <h4 class="section-subheading text-muted text-center">Layanan kami menyediakan bagi Anda</h4>
            <div class="row justify-content-center text-center mt-5">
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fas fa-circle fa-stack-2x service"></i>
                        <i class="fas fa-clock fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">Cepat</h4>
                    <p class="text-muted px-5">Melakukan pembayaran dalam waktu yang sangat singkat.</p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fas fa-circle fa-stack-2x service"></i>
                        <i class="fa-solid fa-desktop fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">Mudah</h4>
                    <p class="text-muted px-5">Permudah pembayaran tagihan listrik rumah Anda secara instan di mana saja dan kapan saja.</p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fas fa-circle fa-stack-2x service"></i>
                        <i class="fa-solid fa-shield fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">Aman</h4>
                    <p class="text-muted px-5">Keamanan dalam menjaga kerahasiaan data anda.</p>
                </div>
            </div>
            <div class="row text-center mt-5">
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fas fa-circle fa-stack-2x service"></i>
                        <i class="fas fa-money-bill-transfer fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">Transfer Bank</h4>
                    <p class="text-muted px-5">Aplikasi yang memudahkan Anda untuk membayar tagihan listrik pascabayar melalui Payment Point Online Banking (PPOB).</p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fas fa-circle fa-stack-2x service"></i>
                        <i class="fas fa-charging-station fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">Electricity</h4>
                    <p class="text-muted px-5">Dapat selalu produktif bersama keluarga Anda tanpa mengkhawatirkan pemadaman listrik.</p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fas fa-circle fa-stack-2x service"></i>
                        <i class="fas fa-file-invoice-dollar fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">Transaction</h4>
                    <p class="text-muted px-5">Semua data tagihan listrik yang telah dibayarkan dapat dilihat kembali untuk memudahkan pemantauan jika terjadi perubahan harga.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Section Sevice End -->
</div>
@endsection
@section('javascript')

@endsection
