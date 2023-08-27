@extends('frontend.layouts.main')

@section('main-container')

  <div class="hero_area">
    <div class="hero_bg_box">
      <img src="{{ url('frontend/images/hero-bg.jpg')}}" alt="">
    </div>
    <!-- slider section -->
    <section class="slider_section ">
      <div id="customCarousel1" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="container ">
              <div class="row">
                <div class="col-lg-10 col-md-11 mx-auto">
                  <div class="detail-box">
                    <h1>
                      Welcome to Fictionale Company Campaign <br>
                    </h1>
                    <div class="btn-box">
                      <a href="{{ url('redeem') }}" class="btn1" style="width: 250px">
                        Go To Redeem Voucher
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
    </section>
    <!-- end slider section -->
  </div>

    <!-- redeem section -->

    <section class="team_section layout_padding">
      <div class="container">
        <div class="heading_container heading_center">
          <h2>
            Product
          </h2>
        </div>
        <div class="row">
          @foreach ($products as $product)
            <div class="col-md-4 col-sm-6 mx-auto">
              <div class="box">
                <div class="detail-box">
                  <p>{{ $product->product_name }}</p>
                </div>
                <div class="img-box">
                  <img src="{{ asset('frontend/images/'. $product->filename)}}" alt="" style="height:250px">
                </div>
                <div class="detail-box">
                  <button type="button" style="background-color: transparent;color:white; border-color:transparent;"class=" purchase" value="{{ $product->id }}">$ {{ $product->price }}</button>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  
    <!-- redeem team section -->

@endsection

@section('scripts')

<script>

  $(document).ready(function(){
    //$('.purchase').submit(function(e) {
    $(document).on('click', '.purchase', function(e){
      e.preventDefault();
      //var product_id = $(this).val();
      //console.log($(this).val());
      var data = {
        'productid': $(this).val(),
      }
      console.log(data);

      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        type: "POST",
        url: "{{ route('purchase') }}",
        data: JSON.stringify(data),
        contentType: "application/json; charset=utf-8",
        //dataType: "json",
        success: function(response){
          swal("Purchased Successfully", "Thank You for purchasing", "success")
        },
        error: function(response){
          console.log(response);
        }
      })
    })
  });
</script>

@endsection