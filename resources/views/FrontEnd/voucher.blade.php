@extends('frontend.layouts.main')

@section('main-container')
  </div>
  
  <!-- redeem section -->

  <section class="team_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          My Voucher
        </h2>
      </div>
      <div class="row">
        @foreach ($vouchers as $voucher)
        <div class="col-md-4 col-sm-6 mx-auto">
          <div class="box">
            <div class="img-box">
              <img src="{{ url('frontend/images/cashVoucher.jpg')}}" alt="">
            </div>
            <div class="detail-box">
              <button type="button" style="background-color: transparent;color:white; border-color:transparent;"class="voucher" value="{{ $voucher->voucher_code }}">View Voucher</button>
              <p> Expired at : {{ $voucher->expiration_date }}</p>
            </div>
          </div>
        </div>
      @endforeach
      </div>
    </div>
  </section>

<!-- end redeem section -->

<!-- Modal -->
<div class="modal fade" id="viewVoucherModal" tabindex="-1" role="dialog" aria-labelledby="viewVoucherModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="width:350px">
      <div class="modal-header center">
        <h5 class="modal-title" id="Title" style="margin-left:20px;">Please copy the voucher code</h5>
        {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> --}}
      </div>
      <div class="modal-body center">
        <input id="voucher_code" readonly style="margin-left:60px; text-align:center;"/>
      </div>
    </div>
  </div>
</div>


@endsection

@section('scripts')

<script>

  $(document).ready(function(){
    $(document).on('click', '.voucher', function(e){
      e.preventDefault();
      var voucher_code = $(this).val();
      $("#voucher_code").val(voucher_code);
      $('#viewVoucherModal').modal('show');

    })
  });
</script>

@endsection