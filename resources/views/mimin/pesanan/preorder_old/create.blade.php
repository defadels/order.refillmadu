@extends('layouts/contentLayoutMaster')

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
  $( document ).ready(function() {
    cari();
    hitung();
});


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
      url: '{{route('mimin.pesanan.preorder.cari-produk')}}',
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

$( ".kuantitas,.cariproduk,.harga_konsumen,#ongkos_kirim,#biaya_tambahan" ).change(function() {
  hitung();
});
$( ".kuantitas,.cariproduk,.harga_konsumen,#ongkos_kirim,#biaya_tambahan" ).keyup(function() {
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
        url: '{{route('mimin.pesanan.preorder.harga')}}' + '?id_produk=' + produk_id,
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
    var total_belanja = 0;

  $('#tabel_pesanan > tbody  > tr').each(function(index) {
   var produk_id = $('.cariproduk').eq(index).val();
   var pelanggan_id = 1;
   var qty = $('.kuantitas').eq(index).val();
   var harga = get_harga(produk_id,pelanggan_id);
   $('.harga_satuan').eq(index).html(numeral(harga).format('0,0'));
   var sub_total = qty*harga;
   $('.sub_total').eq(index).html(numeral(sub_total).format('0,0'));

   if(!isNaN(sub_total)){
    total_belanja += sub_total;
   }

  });

  $('#total_belanja').html(numeral(total_belanja).format('0,0'));

  var ongkir = $('#ongkos_kirim').cleanVal();
  var biaya_tambahan = $('#biaya_tambahan').cleanVal();
  var grand_total =  parseInt(ongkir) + parseInt(biaya_tambahan) +  parseInt(total_belanja);

  $('#grand_total').val(numeral(grand_total).format('0,0'));

}


  var produk = {
      nama : "Minyak goreng",
      kuantitas : 1,
      harga_satuan : 0,
      sub_total : 0,
  };

  function hapus_baris(e){
    $(e).closest('tr').remove();
    hitung();
  }

  function tambah_baris (produk){

      var baris = "<tr>"
                 +"<td><select class='form-control cariproduk' name='produk_id[]'></select></td>"
                 +"<td><div class='input-group input-group-md'><input type='number' name='kuantitas[]' class='form-control kuantitas touchspin' value='"+produk.kuantitas+"'/></div></td>"
                 +"<td class='harga_satuan text-right'>"+produk.harga_satuan+"</td>"
                 +"<td class='sub_total text-right'>"+produk.sub_total+"</td>"
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
                    {!! Form::open(['method' => 'POST','route' => ['mimin.pesanan.preorder.store'],'class'=>'row match-height']) !!}



                        <div class="col-md-6 col-12">
                            <div class="card">
                            <div class="card-header">
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
                                                 <strong>          {{$cabang->nama}} </strong>
                                                 {!!Form::hidden('cabang_id',$cabang->id)!!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Pelanggan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                         <strong>      {{$pelanggan->nama}} [{{$pelanggan->kategori->nama}}]</strong>

                                                 {!!Form::hidden('pelanggan_id',$pelanggan->id)!!}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Leader</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                         <strong>      {{$pelanggan->parent->nama}} [{{$pelanggan->parent->kategori->nama}}]</strong>

                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Dibayar Oleh</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                              <strong>{{$dibayar_oleh}}</strong>

                                                            {!!Form::hidden('dibayar_oleh',$dibayar_oleh)!!}
                                                            </div>
                                                        </div>
                                                    </div>



                                                 <div class="col-md-12 offset-md-4">
                                                        <a href="{{route('mimin.pesanan.preorder.create')}}" class="btn btn-outline-warning mr-1 mb-1"><i class="feather icon-edit"></i> Ganti</a>

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
                                </div>

                                <div class="card-content">
                                    <div class="card-body">
                                            <div class="form-body">
                                                <div class="row">


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
                                                <th scope="col"  class="text-right">Harga Satuan</th>
                                                <th scope="col"  class="text-right">Sub Total</th>
                                                <th width="1%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                              @foreach(old('produk_id',[]) as $index => $produk_uuid)
                                              "<tr>"
                 <td>
                  {!!Form::select('produk_id[]',[$produk_uuid=>$daftar_produk[$produk_uuid]],$produk_uuid,['class'=>'form-control cariproduk','id'=>$produk_uuid])!!}

                </td>
                 <td><div class='input-group input-group-md'>
                   {!!Form::number('kuantitas[]',old('kuantitas')[$index],['class'=>'form-control kuantitas touchspin'])!!}
                  </div>
                 </td>
                 <td class='harga_satuan text-right'></td>
                 <td class='sub_total text-right'></td>
                 <td><a onclick='hapus_baris(this)' class='btn btn-icon btn-outline-warning btn-sm waves-effect waves-light' href='javascript:void(0)'><i class='feather icon-trash'></i></a></td>

                 </tr>
                                              @endforeach
                                            <tr>
                                                <th scope="row" colspan="3">Total</th>
                                                <td id="total_belanja" class="text-right">0</td>
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
                                                                <span>Biaya Tambahan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            {{ Form::text('biaya_tambahan',old('biaya_tambahan'), ['id'=>'biaya_tambahan','class' => 'form-control nominal text-right']) }}
                                                            </div>
                                                        </div>
                                                </div>

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
                                                        <a href="{{route('mimin.pesanan.preorder.index')}}" class="btn btn-outline-warning mr-1 mb-1">Batal</a>

                                                 </div>



                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {!!Form::close()!!}
                    </div>
</section>

@endsection
