@extends('layouts/contentLayoutMaster')

@section('title', $judul)

@section ('tombol_sudut')
@endsection

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
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
@endsection
@section('content')

<!-- Basic Horizontal form layout section start -->
<section id="basic-horizontal-layouts">
                    <div class="row match-height">


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
                                                         <strong>      {{$pesanan->pelanggan->nama}} [{{$pesanan->pelanggan->kategori->nama}}] </strong>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Leader</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                         <strong>      {{$pesanan->pelanggan->parent->nama}} [{{$pesanan->pelanggan->parent->kategori->nama}}] </strong>

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
                                                              {{$pesanan->metode_pembayaran->nama}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Metode Pengiriman</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ $pesanan->metode_pengiriman->nama }}
                                                            </div>
                                                        </div>
                                                    </div>







                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Catatan khusus</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ $pesanan->keterangan }}
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

                             </div>
                            <div class="card-content">

                                <div class="table-responsive">
                                    <table id="tabel_pesanan" class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col" width="30%">Produk
                                                </th>
                                                <th scope="col" width="10%" class="text-center">Jumlah</th>
                                                <th scope="col"  class="text-left">Satuan</th>
                                                <th scope="col"  class="text-right">Harga Satuan</th>
                                                <th scope="col"  class="text-right">Sub Total</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                              @foreach($pesanan->daftar_detil as $index => $detil)
                                              <tr>
                 <td>
                  {{$detil->produk->nama}}

                </td>
                 <td  class="text-center">
                   {{$detil->kuantitas}}

                 </td>
                 <td  class="text-left">
                   {{$detil->produk->satuan}}

                 </td>
                 <td class='harga_satuan text-right'>{{number_format($detil->harga)}}</td>
                 <td class='sub_total text-right'>{{number_format($detil->sub_total)}}</td>

                 </tr>
                                              @endforeach
                                            <tr>
                                                <th scope="row" colspan="4">Total</th>
                                                <td id="total_belanja" class="text-right">{{number_format($pesanan->nominal_pembelian)}}</td>

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
                                                            <div class="col-md-8 text-right">
                                                            {{number_format($pesanan->biaya_tambahan)}}
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Diskon</span>
                                                            </div>
                                                            <div class="col-md-8 text-right">
                                                            {{number_format($pesanan->diskon)}}
                                                            </div>
                                                        </div>
                                                </div>

                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Biaya Packing</span>
                                                            </div>
                                                            <div class="col-md-8 text-right">
                                                            {{number_format($pesanan->biaya_packing)}}
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Ongkos Kirim</span>
                                                            </div>
                                                            <div class="col-md-8 text-right">
                                                            {{number_format($pesanan->ongkos_kirim)}}
                                                            </div>
                                                        </div>
                                                </div>

                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Grand Total </span>
                                                            </div>
                                                            <div class="col-md-8 text-right">
                                                            {{number_format($pesanan->grand_total)}}

                                                            </div>
                                                        </div>
                                                </div>

                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Dibayar</span>
                                                            </div>
                                                            <div class="col-md-8 text-right">
                                                            {{number_format($pesanan->nominal_yang_dibayar)}}
                                                            </div>
                                                        </div>
                                                </div>

                                                <div class="col-md-12">

                                                      <a href="{{route('mimin.pesanan.status.index')}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

                                                 </div>

                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
</section>

@endsection
