@extends('layouts/pelanggan')

@section('title', $judul)

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
@endsection
@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
@endsection
@section('page-script')
<script src="{{ asset(mix('js/scripts/pages/app-index.js')) }}"></script>
@endsection


@section ('tombol_sudut')
<a class="btn btn-icon btn-outline-success btn-sm waves-effect waves-light" href="{{route('mimin.dompet.transaksi.tambah')}}"><i class="feather icon-plus"></i></a>
<a class="btn btn-icon btn-outline-danger btn-sm waves-effect waves-light" href="{{route('mimin.dompet.transaksi.kurangi')}}"><i class="feather icon-minus"></i></a>
@endsection
@section('content')
<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">



                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th width="1%"></th>
                                                <th>Tanggal</th>
                                                <th>Kas</th>

                                                <th>Keterangan</th>
                                                <th>Nominal</th>
                                                <th>Pelanggan</th>
                                                <th width="1%"></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($daftar_rincian as $rincian)
                                          <tr>
                                            <td>
                                            @if($rincian->debet_kredit == "debet")
                                            <div class="badge badge-lg badge-success">
                                                    <i class="feather icon-arrow-down"></i>
                                            </div>
                                            @else
                                            <div class="badge badge-lg badge-danger">
                                                    <i class="feather icon-arrow-up"></i>
                                            </div>

                                            @endif


                                              </td>

                                            <td>{{$rincian->tanggal->format("d-m-Y")}}</td>
                                            <td>{{$rincian->transaksi_kas->kas->nama}}</td>

                                            <td>{{$rincian->keterangan}}</td>
                                            <td style="text-align:right;">{{number_format($rincian->nominal)}}</td>
                                            <td>{{$rincian->user->nama}}</td>
                                            <td>


<a class="btn btn-icon btn-outline-success btn-sm waves-effect waves-light" href="{{route('mimin.dompet.transaksi.lihat',$rincian->id)}}"><i class="feather icon-list"></i></a>
                                            </td>
                                          </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer">

                            {{ $daftar_rincian->appends(request()->input())->links() }}
</div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
