@extends('layouts/contentLayoutMaster')

@section('title', $judul)

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/daterangepicker/daterangepicker.css')) }}">
@endsection
@section('vendor-script')
@endsection
@section('page-script')

<script src="{{ asset(mix('vendors/js/pickers/daterangepicker/moment.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/daterangepicker/daterangepicker.js')) }}"></script>

<script type="text/javascript">
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

{!! Form::open(['method'=>'get','route' => ['mimin.laporan.laba_rugi.index'],'class'=>'form form-inline input-group']) !!}

<div class="input-group-prepend">
<span class="input-group-text" id="basic-addon1"><i class="feather icon-calendar"></i></span>
</div>
{!!Form::text('range',"",['class'=>'form-control','id'=>'reportrange'])!!}
&nbsp;
&nbsp;
  <button type="submit" class="btn btn-primary">Lihat</button>

{!!Form::close()!!}

                                 </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">


                                        <tbody>
                                            <tr>

                                            <td colspan="2"><strong>Pendapatan</td>
                                            <td class="text-right" colspan="2"><strong>{{number_format($daftar_penjualan->sum('total_penjualan'))}}</strong></td>
                                            </tr>
                                            @foreach($daftar_penjualan as $penjualan)
                                            <tr>
                                            <td></td>
                                            <td>{{$penjualan->nama}}</td>
                                            <td class="text-right">{{number_format($penjualan->total_penjualan)}}</td>
                                            <td></td>
                                            </tr>
                                            @endforeach
                                            <tr>

                                            <td colspan="2"><strong>Harga Pokok Penjualan</strong></td>
                                            <td class="text-right" colspan="2"><strong>{{number_format($daftar_penjualan->sum('total_hpp'))}}</strong></td>
                                            </tr>
                                            @foreach($daftar_penjualan as $penjualan)
                                            <tr>
                                            <td></td>
                                            <td>{{$penjualan->nama}}</td>
                                            <td class="text-right">{{number_format($penjualan->total_hpp)}}</td>
                                            <td></td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                            <td colspan="2"><strong>Biaya</strong></td>

                                            <td class="text-right" colspan="2"><strong>{{number_format($daftar_kategori_pengeluaran->sum('total_biaya'))}}</strong></td>
                                            </tr>
                                            @foreach($daftar_kategori_pengeluaran as $pengeluaran)
                                            <tr>
                                            <td></td>
                                            <td>{{$pengeluaran->nama}}</td>
                                            <td class="text-right">{{number_format($pengeluaran->total_biaya)}}</td>
                                            <td></td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                            <td colspan="2"><strong>Laba Rugi</strong></td>

                                            <td class="text-right" colspan="2"><strong>{{number_format($daftar_penjualan->sum('total_penjualan')-$daftar_penjualan->sum('total_hpp')-$daftar_kategori_pengeluaran->sum('total_biaya'))}}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer">

                           </div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
