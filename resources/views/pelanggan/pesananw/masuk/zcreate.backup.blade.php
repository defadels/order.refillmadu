@extends('layouts/pelanggan')

@section('title', $judul)

@section ('tombol_sudut')
@endsection

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')) }}">

@endsection
@section('vendor-script')
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')) }}"></script>

<script src="{{ asset(mix('js/scripts/jquery.mask.min.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/numeral.min.js')) }}"></script>
@endsection
@section('page-script')
<script>
$('.select2').select2();
$('.caripelanggan').select2({
    placeholder: 'Cari dan Pilih Pelanggan...',
    ajax: {
      url: '{{route('mimin.inventori.retur-barang.cari-pelanggan')}}',
      dataType: 'json',
      delay: 250,
      data: function (params) {
          return {
            cari: params.term,
            page: params.page || 1
          };
        },
      processResults: function (data) {
        return {
          results:  $.map(data.results, function (item) {
            return {
              text: item.nama +" ["+item.email+"]",
              id: item.id
            }
          }),
          pagination: data.pagination
        };
      },
      cache: true
    }
  });
cari();

function cari(){


  $("form").submit(function (event) {
            $('.nominal').unmask();
    });


$('.nominal').mask("000.000.000.000.000", {reverse: true});


  $(".touchspin").TouchSpin({
    buttondown_class: "btn btn-primary",
    buttonup_class: "btn btn-primary",
  });

$('.cariproduk').select2({
    placeholder: 'Cari dan Pilih Produk...',
    ajax: {
      url: '{{route('pelanggan.pesanan.masuk.cari-produk')}}',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        var selainx = $(".cariproduk").map(function(){return $(this).val();}).get().join(',');
          return {
            selain: selainx,
            cari: params.term,
            page: params.page || 1
          };
        },
      processResults: function (data) {
        return {
          results:  $.map(data.results, function (item) {

            return {
              text: item.nama,
              id: item.id
            }
          }),
          pagination: data.pagination
        };
      },
      cache: true
    }
  });

$( ".kuantitas,.cariproduk,.harga_konsumen,#ongkos_kirim" ).change(function() {
  hitung();
});
$( ".kuantitas,.cariproduk,.harga_konsumen,#ongkos_kirim" ).keyup(function() {
  hitung();
});

}



function get_harga(produk_id,pelanggan_id){
  var harga = {!!$daftar_harga!!};
  return harga[produk_id];
}
function get_hargax(produk_id,pelanggan_id){
  var hasil =0;
  if (isNaN(produk_id)){
    return 0;
  }
    $.ajax({
        url: '{{route('pelanggan.pesanan.masuk.harga')}}' + '?id_produk=' + produk_id,
        success: function (data) {
          hasil = data;
        },
        async: false
    });
  return hasil;
}

function hitung(){
   // var x = $(".cariproduk")
  //            .map(function(){return $(this).val();}).get().join(',');
  //  console.log(x);
    var total_belanja_res = 0;
    var total_belanja_kon = 0;

  $('#tabel_pesanan > tbody  > tr').each(function(index) {
   var produk_id = $('.cariproduk').eq(index).val();
   var pelanggan_id = 1;
   var qty = $('.kuantitas').eq(index).val();
   var harga_res = get_harga(produk_id,pelanggan_id);
   $('.harga_reseller').eq(index).html(numeral(harga_res).format('0,0'));
   var sub_total_res = qty*harga_res;
   $('.total_harga_reseller').eq(index).html(numeral(sub_total_res).format('0,0'));
   var harga_kon = 0;
   if ($('.harga_konsumen').eq(index).val()) {

   harga_kon = $('.harga_konsumen').eq(index).cleanVal();

   }

   var sub_total_kon = qty*harga_kon;
   $('.total_harga_konsumen').eq(index).html(numeral(sub_total_kon).format('0,0'));
   if(!isNaN(sub_total_res)){
    total_belanja_res += sub_total_res;
   }
   if(!isNaN(sub_total_kon)){
    total_belanja_kon += sub_total_kon;
   }

  });

  $('#total_belanja_res').html(numeral(total_belanja_res).format('0,0'));
  $('#total_belanja_kon').html(numeral(total_belanja_kon).format('0,0'));

  var ongkir = $('#ongkos_kirim').cleanVal();
  var grand_total =  parseInt(ongkir) +  parseInt(total_belanja_res);

  $('#grand_total').val(grand_total);

}


  var produk = {
      nama : "Minyak goreng",
      kuantitas : 1,
      harga_reseller : 0,
      total_harga : 0,
      harga_konsumen : 0,
      total_harga_konsumen : 0
  };
  function hapus_baris(e){
    $(e).closest('tr').remove();
    hitung();
  }
  function tambah_baris (produk){

      var baris = "<tr>"
                 +"<td><select class='form-control cariproduk' name='produk_id[]'></select></td>"
                 +"<td><div class='input-group input-group-md'><input type='number' name='kuantitas[]' class='form-control kuantitas touchspin' value='"+produk.kuantitas+"'/></div></td>"
                 +"<td class='harga_reseller text-right'>"+produk.harga_reseller+"</td>"
                 +"<td class='total_harga_reseller text-right'>"+produk.total_harga+"</td>"
                 +"<td><input type='text' name='harga_konsumen[]' class='form-control harga_konsumen text-right nominal' value='"+produk.harga_konsumen+"'/></td>"
                 +"<td class='total_harga_konsumen text-right'>"+produk.total_harga_konsumen + "</td>"
                 +"<td><a onclick='hapus_baris(this)' class='btn btn-icon btn-outline-warning btn-sm waves-effect waves-light' href='javascript:void(0)'><i class='feather icon-trash'></i></a></td>"
                 +"</tr>";
      $('#tabel_pesanan > tbody > tr').eq(-1).before(baris);
      cari();
      hitung();
  }
</script>
@endsection
@section('content')

<!-- Basic Horizontal form layout section start -->
<section id="basic-horizontal-layouts">
                    <div class="row match-height">
                        <div class="col-md-6 col-12">
                            <div class="card">
                            <div class="card-header">
                                    <h4 class="card-title">Pembayaran</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                            <div class="form-body">
                                                <div class="row">
                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Pelanggan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::select('pelanggan_id',[],old('pelanggan_id'), ['class' => 'form-control caripelanggan']) }}
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Metode Pembayaran</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::select('metode_pembayaran_id',$daftar_pembayaran,old('metode_pembayaran_id'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Dibayar Oleh</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            {{ Form::select('pembayar',$daftar_pembayar,old('pembayar'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
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
                                    <h4 class="card-title">Pengiriman</h4>
                                </div>

                                <div class="card-content">
                                    <div class="card-body">
                                            <div class="form-body">
                                                <div class="row">

                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Diproses Oleh</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::select('cabang_id',$daftar_cabang,old('cabang_id'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>


                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Metode Pengiriman</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::select('metode_pengiriman_id',$daftar_pengiriman,old('metode_pengiriman_id'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>







                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Catatan khusus</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::text('keterangan',old('keterangan'), ['class' => 'form-control']) }}
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
                                <h4 class="card-title">Barang yang Dipesan</h4>

                                <a onclick="tambah_baris(produk)" class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="javascript:void(0)"><i class="feather icon-plus"></i></a>
                            </div>
                            <div class="card-content">

                                <div class="table-responsive">
                                    <table id="tabel_pesanan" class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col" width="30%">Produk
                                                </th>
                                                <th scope="col" width="10%" class="text-center">Jumlah</th>
                                                <th scope="col">Harga Reseller</th>
                                                <th scope="col">Total Reseller</th>
                                                <th scope="col" width="15%">Harga Invoice</th>
                                                <th scope="col">Total Invoice</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <th scope="row" colspan="3">Total</th>
                                                <td id="total_belanja_res" class="text-right">0</td>
                                                <td></td>
                                                <td id="total_belanja_kon" class="text-right">0</td>
                                                <td></td>
                                              </tr>
                                        </tbody>
                                    </table>
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
                                                                {{ Form::text('dari_nama',old('dari_nama'), ['class' => 'form-control','placeholder'=>'nama']) }}
                                                            </div>
                                                            <div class="col-md-4">
                                                                {{ Form::text('dari_nomor_hp',old('dari_nomor_hp'), ['class' => 'form-control','placeholder'=>'nomor hp']) }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-3">
                                                                <span>Kepada</span>
                                                            </div>
                                                            <div class="col-md-5">
                                                                {{ Form::text('kepada_nama',old('kepada_nama'), ['class' => 'form-control','placeholder'=>'nama']) }}
                                                            </div>
                                                            <div class="col-md-4">
                                                                {{ Form::text('kepada_nomor_hp',old('kepada_nomor_hp'), ['class' => 'form-control','placeholder'=>'nomor hp']) }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-3">
                                                                <span>Alamat Tujuan</span>
                                                            </div>
                                                            <div class="col-md-9">
                                                                {{ Form::textarea('alamat_tujuan',old('alamat_tujuan'), ['class' => 'form-control','rows'=>3]) }}
                                                            </div>
                                                        </div>
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
                                    <h4 class="card-title">Ringkasan</h4>
                                </div>

                                <div class="card-content">
                                    <div class="card-body">
                                            <div class="form-body">
                                                <div class="row">



                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Ongkos Kirim</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            {{ Form::text('ongkos_kirim',old('ongkos_kirim'), ['id'=>'ongkos_kirim','class' => 'form-control nominal text-right']) }}
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Grand Total </span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::text('grand_total',0, ['id'=>'grand_total','class' => 'form-control nominal text-right', 'disabled'=>'disabled']) }}
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Dibayar</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            {{ Form::text('dibayar',old('dibayar'), ['id'=>'dibayar','class' => 'form-control nominal text-right']) }}
                                                            </div>
                                                        </div>
                                                </div>

                                                <div class="col-md-12 offset-md-4">
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1">Pesan</button>
                                                        <a href="{{route('pelanggan.pesanan.masuk.index')}}" class="btn btn-outline-warning mr-1 mb-1">Batal</a>

                                                    </div>



                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
</section>

@endsection
