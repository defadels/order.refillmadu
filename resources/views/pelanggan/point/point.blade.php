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
@endsection
@section('content')
<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                <div class="row">
                                  <div class="col-3 col-md-4">
                                  Nama
                                  </div>
                                  <div class="col-9 col-md-8">
                                      <strong> {{$pelanggan->nama}}</strong>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-3 col-md-4">
                                 Homor Hp
                                  </div>
                                  <div class="col-9 col-md-8">
                                      <strong> {{$pelanggan->nomor_hp}}</strong>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-3 col-md-4">
                                  Kategori
                                  </div>
                                  <div class="col-9 col-md-8">
                                      <strong> {{$pelanggan->kategori->nama}}</strong>
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="col-3 col-md-4">
                                 Point
                                  </div>
                                  <div class="col-9 col-md-8">
                                      <strong> {{number_format($pelanggan->point)}}</strong>
                                  </div>
                                </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th width="1%"></th>
                                                <th>Tanggal</th>

                                                <th>Keterangan</th>

                                                <th>Point</th>
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
                                            <td>{{$rincian->keterangan}}</td>
                                            <td style="text-align:right;">{{number_format($rincian->nominal)}}</td>
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
