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

{!! Form::open(['method'=>'get','route' => ['mimin.laporan.penjualan.laporan5'],'class'=>'form form-inline input-group']) !!}

<div class="input-group-prepend">
<span class="input-group-text" id="basic-addon1"><i class="feather icon-calendar"></i></span>
</div>
{!!Form::select('bulan',$daftar_bulan,$bulan,['class'=>'form-control'])!!}
&nbsp;
{!!Form::select('tahun',$daftar_tahun,$tahun,['class'=>'form-control'])!!}
&nbsp;
{!!Form::text('cari',$cari,['class'=>'form-control','placeholder'=>'Cari Nama / No Pelanggan'])!!}

&nbsp;
{!!Form::select('kategori',$daftar_kategori,$kategori,['class'=>'form-control'])!!}

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
                                            <tr>
                                                <th>Nama</th>
                                                <th>Kategori</th>
                                                <th>Leader</th>
                                                <th>{{$duabulan_lalu_start->format("M Y")}}</th>
                                                <th>{{$sebulan_lalu_start->format("M Y")}}</th>
                                                <th>{{$start_date->format("M Y")}}</th>
                                                <th>Omset (Rp)</th>
                                                <th>Kontribusi</th>
                                            </tr>

                                            </tr>
                                        </thead>

                                        <tbody>
                                          @forelse($daftar_pelanggan as $pelanggan)
                                          <tr>

                                          <td>
                                                    {{$pelanggan->nama}}
                                                    @if($pelanggan->kode)
                                                   <br> {{$pelanggan->kode}}
                                                    @endif

                                                </td>
                                                <td>{{$pelanggan->kategori->nama}}</td>
                                                <td>
                                                    {{$pelanggan->parent->nama}}</br>
                                                    [{{$pelanggan->distributor->nama}}]
                                                </td>
                                                <td style="text-align:right">{{number_format(($pelanggan->omset_kilo_2bln_lalu +$pelanggan->omset_kilo_downline_2bln_lalu) / 1000,1,',','.')}}</td>
                                                <td style="text-align:right">{{number_format(($pelanggan->omset_kilo_1bln_lalu +$pelanggan->omset_kilo_downline_1bln_lalu) / 1000,1,',','.')}}</td>
                                                <td style="text-align:right">{{number_format(($pelanggan->omset_kilo +$pelanggan->omset_kilo_downline) / 1000,1,',','.')}}</td>
                                                <td style="text-align:right">{{number_format($pelanggan->total_penjualan + $pelanggan->total_penjualan_downline)}}</td>
                                                @if(($total_omset_kilo) != 0)
                                                <td style="text-align:center">{{number_format(($pelanggan->omset_kilo + $pelanggan->omset_kilo_downline)/($total_omset_kilo) *100,2,',','.')}} %</td>
                                                @else
                                                <td style="text-align:center">0 % </td>
                                                @endif

                                          </tr>
                                          @empty
                                          <tr><td colspan="6">Tidak ada pelanggan.</td></tr>
                                          @endforelse
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer">

                            {{ $daftar_pelanggan->appends(request()->input())->links() }}
                           </div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
