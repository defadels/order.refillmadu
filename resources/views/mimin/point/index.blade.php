@extends('layouts/contentLayoutMaster')

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
<a href="{{route('mimin.point.transaksi')}}" class="btn-icon btn btn-outline-warning btn-round waves-effect waves-light" aria-haspopup="true" aria-expanded="false"><i class="feather icon-list"></i> Riwayat Transaksi</a>
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
                                                <th>Nama</th>
                                                <th>Nomor Hp</th>
                                                <th>Point</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($daftar_pelanggan as $pelanggan)
                                            <tr>

                                                <td>
                                                    {{$pelanggan->nama}}

                                                </td>
                                                <td>{{$pelanggan->nomor_hp}}</td>
                                                <td style="text-align:right;">{{number_format($pelanggan->point)}}</td>

                                            <td  style="text-align:center;">

                                              <a class="btn btn-icon btn-outline-success btn-sm waves-effect waves-light" href="{{ route('mimin.point.pelanggan.tambah',$pelanggan->id) }}"><i class="feather icon-plus"></i></a>
                                              <a class="btn btn-icon btn-outline-danger btn-sm waves-effect waves-light" href="{{ route('mimin.point.pelanggan.kurangi',$pelanggan->id) }}"><i class="feather icon-minus"></i></a>
                                              <a class="btn btn-icon btn-outline-primary btn-sm waves-effect waves-light" href="{{ route('mimin.point.pelanggan.index',$pelanggan->id) }}"><i class="feather icon-list"></i></a>

                                                </td>
                                            </tr>
                                        @endforeach

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
