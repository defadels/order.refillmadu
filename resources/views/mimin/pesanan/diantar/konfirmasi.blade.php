@extends('layouts/contentLayoutMaster')

@section('title', $judul)

@section ('tombol_sudut')
@endsection



@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/validation/jqBootstrapValidation.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/jquery.mask.min.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/numeral.min.js')) }}"></script>
@endsection

@section('page-script')
<script>
$('.confirm').on('click', function (e) {
Swal.fire({
  title: 'Yakin ingin '+ e.target.getAttribute('data-namaaksi')+' pesanan ini?',
  text: "",
  type: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Ya, Yakin!',
  confirmButtonClass: 'btn btn-danger',
  cancelButtonClass: 'btn btn-primary ml-1',
  buttonsStyling: false,
}).then(function (result) {
  if (result.value) {

    var token =  $('meta[name="csrf-token"]').attr('content');
    var id=3;
    $.ajax({
        url: e.target.getAttribute('data-aksi'),
        type: 'DELETE',
        data: {
             "_token": token,
        },
        success: function(result) {
            // Do something with the result
            Swal.fire(
              {
                type: result.success?"success":"warning",
                title: result.judul,
                text:  result.pesan,
                confirmButtonClass: 'btn btn-success',
              }
            ).then(function (a) {
               if (result.success){
                location.href = result.redirect;
               }
            });
        }
    });

  }

});
});
</script>
<script>
$('.select2').select2();
// Loading remote data
$(document).ready( function() {
    // Format Date Picker
    $('.tanggal').pickadate({
        selectYears: true,
        selectMonths: true,
        format: 'dd-mm-yyyy'
    });
    $('.nominal').mask("000.000.000.000.000", {reverse: true});
    $("form").submit(function (event) {
            $('.nominal').unmask();
    });

    numeral.register('locale', 'id', {
    delimiters: {
        thousands: '.',
        decimal: ','
    },
    abbreviations: {
        thousand: 'rb',
        million: 'jt',
        billion: 'M',
        trillion: 'T'
    },
    ordinal : function (number) {
        return number === 1 ? 'er' : 'ème';
    },
    currency: {
        symbol: 'Rp'
    }
});

// switch between locales
numeral.locale('id');
});

 </script>


</script>

@endsection
@section('content')

<!-- Basic Horizontal form layout section start -->
<section id="basic-horizontal-layouts">
                    <div class="row match-height">


                        <div class="col-md-6 col-12">
                            <div class="card">

                                <div class="card-content">
                                    <div class="card-body">
                                            <div class="form-body">
                                                <div class="row">
                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Diantar Oleh</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                 <strong>          {{$pesanan->cabang->nama}} </strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Pelanggan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                         <strong>      {{$pesanan->pelanggan->nama}}  [{{$pesanan->pelanggan->kategori->nama}}]</strong>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Leader</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                         <strong>      {{$pesanan->pelanggan->parent->nama}}  [{{$pesanan->pelanggan->parent->kategori->nama}}]</strong>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Distributor</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                         <strong>      {{$pesanan->pelanggan->distributor->nama}} </strong>

                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Diantar pada</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                              <strong>{{$pesanan->diantar_pada->format('d F Y')}}</strong>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Diantar oleh</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                              <strong>{{$pesanan->diantar_oleh->nama}}</strong>

                                                            </div>
                                                        </div>
                                                    </div>



                                                 <div class="col-md-12 offset-md-4">
                                                 </div>


                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="card">
                            <div class="card-header">
                                    <h4 class="card-title">Alamat Pengiriman</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                            <div class="form-body">
                                                <div class="row">
                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-3">
                                                                <span>Dari</span>
                                                            </div>
                                                            <div class="col-md-5">
                                                               {{$pesanan->dikirim_oleh}}
                                                            </div>
                                                            <div class="col-md-4">
                                                                {{$pesanan->nomor_hp_pengirim}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-3">
                                                                <span>Kepada</span>
                                                            </div>
                                                            <div class="col-md-5">
                                                                {{ $pesanan->dikirim_kepada }}
                                                            </div>
                                                            <div class="col-md-4">
                                                                {{ $pesanan->nomor_hp_tujuan }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-3">
                                                                <span>Alamat Tujuan</span>
                                                            </div>
                                                            <div class="col-md-9">
                                                              {{$pesanan->alamat_tujuan}}
                                                            </div>
                                                        </div>
                                                    </div>




                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-12">
                        <div class="card">
                            <div class="card-header mb-2">
                                <h4 class="card-title">Konfirmasi Pengiriman</h4>

                             </div>
                            <div class="card-content">

                            <div class="card-body">
                            {!! Form::model($pesanan,['method' => 'PATCH','route' => ['mimin.pesanan.diantar.update',$pesanan->id],'class'=>'form form-horizontal']) !!}
                                    <div class="form-body">
                                                <div class="row">


                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Produk diambil dari</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            {{ Form::select('gudang_id',$daftar_gudang,old('gudang_id'), ['class' => 'form-control','disabled'=>'true']) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Dikirim Oleh</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::select('diantar_oleh_id',$daftar_kurir,old('diantar_oleh_id'), ['class' => 'form-control','disabled'=>'true']) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Metode Pengiriman</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            {{ Form::select('metode_pengiriman_id',$daftar_metode_pengiriman,old('metode_pengiriman_id'), ['class' => 'form-control select2']) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Tanggal Pengiriman</span>
                                                            </div>
                                                            <div class="col-md-8 input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="feather icon-calendar"></i></span>
                                                                </div>
                                                                {{ Form::text('tanggal_pengiriman',old('tanggal_pengiriman')?old('tanggal_pengiriman'):($pesanan->tanggal_pengiriman?$pesanan->tanggal_pengiriman->format("d-m-Y"):""), ['class' => 'form-control tanggal','required']) }}

                                                            </div>
                                                        </div>
                                                    </div>



                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Nomor Resi</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::text('nomor_resi',old('nomor_resi'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Berat/Jarak (gram/kilometer)</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::number('bobot_pengiriman',old('bobot_pengiriman'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Ongkos Kirim (Jasa Kurir)</span>
                                                            </div>
                                                            <div class="col-md-8 input-group">
                                                            <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1">Rp.</span>
                                                                </div>
                                                            {{ Form::text('ongkos_kirim',old('ongkos_kirim'), ['class' => 'form-control nominal','required']) }}
                                                             </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Ongkos Kurir (Biaya Antar ke Kurir)</span>
                                                            </div>
                                                            <div class="col-md-8 input-group">
                                                            <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1">Rp.</span>
                                                                </div>
                                                            {{ Form::text('ongkos_kurir',old('ongkos_kurir'), ['class' => 'form-control nominal','required']) }}
                                                             </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Biaya Packing</span>
                                                            </div>
                                                            <div class="col-md-8 input-group">
                                                            <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1">Rp.</span>
                                                                </div>
                                                            {{ Form::text('biaya_packing',old('biaya_packing'), ['class' => 'form-control nominal','required']) }}
                                                             </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-12 offset-md-4">

@canany(['pesanan.diantar.kirim.simpan','pesanan.diantar.kirim.konfirmasi'])
                                                        <button type="submit" name="simpan" class="btn btn-primary mr-1 mb-1">Simpan</button>

@endcanany

@can(['pesanan.diantar.kirim.konfirmasi'])
                                                      <button type="submit" name="konfirmasi" class="btn btn-danger mr-1 mb-1">Konfirmasi Pengiriman</button>
@endcan
                                                        <a href="{{route('mimin.pesanan.diantar.index')}}" class="btn btn-outline-warning mr-1 mb-1">Batal</a>
                                                 </div>

                                                </div>
                                            </div>
                                        {!!Form::close()!!}

                            </div>
                            </div>
                        </div>
                        </div>


                    </div>
</section>

@endsection
