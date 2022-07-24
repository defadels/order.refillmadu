@extends('layouts/contentLayoutMaster')

@section('title', $judul)

@section('vendor-style')

<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/daterangepicker/daterangepicker.css')) }}">
@endsection
@section('vendor-script')
@endsection
@section('page-script')


<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')) }}"></script>


<script src="{{ asset(mix('vendors/js/pickers/daterangepicker/moment.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/daterangepicker/daterangepicker.js')) }}"></script>

<script type="text/javascript">

$('.caripelanggan').select2({
    placeholder: 'Cari dan Pilih Pelanggan...',
    ajax: {
      url: '{{route('mimin.laporan.penjualan.pelanggan.cari')}}',
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
$(function() {

    var start = moment("{{$start_date->format('Y-m-d')}}");
    var end = moment("{{$end_date->format('Y-m-d')}}");

    function cb(start, end) {
        $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        locale: {
          format: 'DD/MM/YYYY'
        },
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

});
</script>
@endsection


@section ('tombol_sudut')
@endsection
@section('content')
<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">

{!! Form::open(['method'=>'get','route' => ['mimin.laporan.penjualan.laporan3'],'class'=>'form form-inline input-group']) !!}

<div class="input-group-prepend">
<span class="input-group-text" id="basic-addon1"><i class="feather icon-calendar"></i></span>
</div>
{!!Form::text('range',"",['class'=>'form-control','id'=>'reportrange'])!!}


&nbsp;

{!!Form::select('pelanggan',$daftar_pelanggan,$pelanggan,['class'=>'form-control caripelanggan'])!!}
&nbsp;
{!!Form::select('cabang',$daftar_cabang,$cabang,['class'=>'form-control'])!!}

&nbsp;
  <button type="submit" class="btn btn-primary">Lihat</button>

{!!Form::close()!!}

                                 </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Tanggal Bayar</th>
                                                <th>Produk</th>
                                                <th>Pelanggan</th>
                                                <th>Total Pesanan</th>
                                                <th>Pembayaran</th>
                                                <th>Pengiriman</th>
                                                <th>Ongkos Kirim</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @forelse ($daftar_pesanan as $pesanan)
                                            <tr>

                                                <td>
                                                    {{$pesanan->tanggal_pembayaran->format('d-m-Y')}}
                                                </td>
                                                <td>
                                                    {{Str::limit($pesanan->daftar_detil->pluck('produk.nama')->implode(", "),30)}}
                                                </td>

                                                <td>
                                                  {{$pesanan->pelanggan->nama}}
                                                </td>

                                                <td class="text-right">
                                                  {{number_format($pesanan->nominal_pembelian)}}
                                                </td>
                                                <td>
                                                  {{$pesanan->metode_pembayaran->nama}}
                                                </td>
                                                <td class="text-center">

                                                {{$pesanan->metode_pengiriman->nama}}

                                                </td>
                                                <td class="text-right">
                                                  {{number_format($pesanan->ongkos_kirim)}}
                                                </td>


                                            </tr>
                                        @empty
                                        <tr>

                                        <td colspan="7">Tidak ada penjualan dari {{$start_date->format("d-m-Y")}} hingga {{$end_date->format("d-m-Y")}}</td>
                                        </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer">

                            {{ $daftar_pesanan->appends(request()->input())->links() }}
                           </div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
