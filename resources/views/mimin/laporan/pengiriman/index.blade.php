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

{!! Form::open(['method'=>'get','route' => ['mimin.laporan.pengiriman.index'],'class'=>'form form-inline input-group']) !!}

<div class="input-group-prepend">
<span class="input-group-text" id="basic-addon1"><i class="feather icon-calendar"></i></span>
</div>
{!!Form::text('range',"",['class'=>'form-control','id'=>'reportrange'])!!}
&nbsp;
{!!Form::select('pengiriman',$daftar_pengiriman,$pengiriman,['class'=>'form-control'])!!}
&nbsp;
{!!Form::text('resi',isset($resi)?$resi:"",['class'=>'form-control','placeholder'=>'Nomor Resi'])!!}

&nbsp;
  <button type="submit" class="btn btn-primary">Lihat</button>

{!!Form::close()!!}

                                 </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Tanggal Kirim</th>
                                                <th>Pengiriman</th>
                                                <th>Nomor Resi</th>
                                                <th>Dari</th>
                                                <th>Kepada</th>
                                                <th>Ongkir</th>
                                                <th>Bobot</th>
                                                <th>Kurir</th>
                                                <th>Diinput Oleh</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @forelse ($daftar_pesanan as $pesanan)
                                            <tr>

                                                <td>
                                                    {{$pesanan->tanggal_pengiriman->format('d-m-Y')}}
                                                </td>
                                                <td>
                                                    {{$pesanan->metode_pengiriman->nama}}
                                                </td>

                                                <td>
                                                  {{$pesanan->nomor_resi}}
                                                </td>

                                                <td>
                                                  {{$pesanan->dikirim_oleh}}<br>
                                                  {{$pesanan->nomor_hp_pengirim}}
                                                </td>

                                                <td>
                                                  {{$pesanan->dikirim_kepada}}<br>
                                                  {{$pesanan->nomor_hp_tujuan}}
                                                </td>

                                                <td class="text-right">

                                                   {{number_format($pesanan->ongkos_kirim)}}
                                                </td>   <td class="text-right">

                                                  {{number_format($pesanan->bobot_pengiriman)}}
                                                  </td>
                                                <td>

                                                    {{$pesanan->diantar_oleh->nama}}

                                                    </td>
                                                <td>

                                                {{$pesanan->pengiriman_diinput_oleh->nama}}

                                                </td>



                                            </tr>
                                        @empty
                                        <tr>

                                        <td colspan="9">Tidak ada pengiriman dari {{$start_date->format("d-m-Y")}} hingga {{$end_date->format("d-m-Y")}}</td>
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
