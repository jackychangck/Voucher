@extends('frontend.layouts.main')

@section('main-container')
  </div>

  <!-- redeem section -->
  {{-- generate voucher --}}
  {{-- <button type="button" style="background-color: black;color:white; border-color:transparent;" id="generateVoucherCode" value="">Generate Voucher</button> --}}

  <section class="team_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Voucher Redemption Page
        </h2>
      </div>
      <div class="row">
        {{-- check how many events --}}
        <div class="col-md-4 col-sm-6 mx-auto">
          <div class="box">
            <div class="img-box">
              <img src="{{ url('frontend/images/cashVoucher.jpg')}}" alt="">
            </div>
            <div class="detail-box">
              {{-- check if still have voucher--}}
              @if ($availableVouchers)
                {{-- check if user redeemed this voucher --}}
                @if ($isRedeemed)
                  <p style="background-color: transparent;color:red;font-style:bold; border-color:transparent;">Redeemed</p>
                @else
                  {{-- check if user redeemed this voucher --}}
                  @if($term)
                    <button type="button" style="background-color: transparent;color:white; border-color:transparent;" class="upload" value="{{ $event->id }}">Click to Redeem</button>
                  @else
                    <p style="background-color: transparent;color:red;font-style:bold; border-color:transparent;">Didn't fulfill term and conditions</p>
                  @endif
                @endif
              @else
                  <p style="background-color: transparent;color:red;font-style:bold; border-color:transparent;">Out of Stock</p>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end redeem section -->

<!-- Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalCenterTitle" aria-hidden="true" >
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="width:600px">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Upload Photo</h5>
        <h5 class="modal-title time" style="color:red; margin-left: 150px;"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form id="myform" action="{{ route('redeem') }}" method="POST" enctype="multipart/form-data"> 
              @csrf
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <input type="file" name="image" placeholder="Choose image" id="image">
                      </div>
                  </div>
                  <div class="col-md-12 mb-2">
                      <img id="preview-image-before-upload" alt="preview image" style="max-height: 250px;">
                  </div>
                  <input type="text" style="visibility:hidden;" name="eventid" id="eventid" value="{{ $event->id }}"/>
                  <div class="col-md-12">
                      <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                  </div>
              </div>     
          </form>
      </div>
      {{-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> --}}
    </div>
  </div>
</div>
@endsection

@section('scripts')

<script>
  $(document).ready(function(){
    var refreshIntervalId;
    var myTimeout;
    $('#uploadModal').on('hidden.bs.modal', function () {
      console.log('close');
      $("#preview-image-before-upload").attr("src","");
      $("#image").val("");
      clearInterval(refreshIntervalId);
      clearTimeout(myTimeout);
    })

    $(document).on('click', '.upload', function(e){
      console.log('open');      
      var data = {
        'eventid': $(this).val()
      }
      $("#uploadModal").modal('show');

      var seconds = 60;
      var minutes = 10;
      $('.time').text(minutes + ':00 Left time');
      
      refreshIntervalId = setInterval(() =>{
        if(seconds <= 0){
          minutes--;
          seconds = 60;
        }
        seconds--;
        if(seconds >= 10){
          $('.time').text(minutes-1 + ':' + seconds + ' Left time');
        }
        else{
          $('.time').text(minutes-1 + ':0' + seconds + ' Left time');
        }

      }, 1000);

      myTimeout = setTimeout(function(){
          $("#uploadModal").modal('hide');
          swal("Session Timeout", "Please resubmit the photo.", "error");
      }, 600000);
    })

    $(document).on('click', '.close', function(e){
      e.preventDefault();

      $("#uploadModal").modal('hide');
    })

    $(document).on('click', '#close', function(e){
      e.preventDefault();

      $("#uploadModal").modal('hide');
    })
    
    $('#image').change(function(){
      let reader = new FileReader();
      reader.onload = (e) => { 
        $('#preview-image-before-upload').attr('src', e.target.result); 
      }
      reader.readAsDataURL(this.files[0]); 
    });
    
    $('#image-upload').submit(function(e) {
      e.preventDefault();
    
      var formData = new FormData(this);
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
          type:'POST',
          url: "{{ url('redeem')}}",
          data: formData,
          datatype: json,
          cache:false,
          contentType: false,
          processData: false,
          success: (data) => {
            this.reset();
            console.log(data);
          },
          error: function(data){
            console.log(data);
          }
        });
    });

    $(document).on('click', '#generateVoucherCode', function(e){
      e.preventDefault();
      var data = {
        'data': $(this).val()
      }
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
          type:'POST',
          url: "{{ route('generateVoucherCode')}}",
          data: JSON.stringify(data),
          contentType: "application/json; charset=utf-8",
          success: (data) => {
            console.log(data);
          },
          error: function(data){
            console.log(data);
          }
        });
    })
  })


</script>

@endsection
