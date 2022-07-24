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
<a href="{{route('mimin.pengaturan.provinsi.kabupaten.kecamatan.create',[$provinsi->id,$kabupaten->id])}}" class="btn-icon btn btn-outline-warning btn-round waves-effect waves-light" aria-haspopup="true" aria-expanded="false"><i class="feather icon-plus"></i> Tambah</a>

@endsection
@section('content')
<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                <div class="row">
                                  <div class="col-4 col-md-2">Provinsi</div>
                                  <div class="col-8 col-md-10">: <strong>{{$provinsi->nama}}</strong></div>
</div>
<div class="row">
                                  <div class="col-4 col-md-2">Kabupaten</div>
                                  <div class="col-8 col-md-10">: <strong>{{$kabupaten->nama}}</strong></div>
</div>
                                 </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($daftar_kecamatan as $kecamatan)
                                            <tr>

                                                <td>
                                                    {{$kecamatan->nama}}

                                                </td>
                                                <td>
                                                    {{$kecamatan->status}}

                                                </td>

                                            <td  style="text-align:center;">

                                              <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{ route('mimin.pengaturan.provinsi.kabupaten.kecamatan.edit',[$provinsi->id,$kabupaten->id,$kecamatan->id]) }}"><i class="feather icon-edit"></i></a>
                                              <a class="btn btn-icon btn-outline-danger btn-sm waves-effect waves-light confirm-delete" data-nama="{{$kecamatan->nama}}"  data-aksi="{{route('mimin.pengaturan.provinsi.kabupaten.kecamatan.destroy',[$provinsi->id,$kabupaten->id,$kecamatan->id])}}" href="javascript:void(0)">
                                                <i class="feather icon-trash" data-nama="{{$kecamatan->nama}}"  data-aksi="{{route('mimin.pengaturan.provinsi.kabupaten.kecamatan.destroy',[$provinsi->id,$kabupaten->id,$kecamatan->id])}}"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer">

                            {{ $daftar_kecamatan->appends(request()->input())->links() }}
                           </div>
                        </div>
                    </div>
                </div>
<div class="row">
  <div class="col-12">
    <a href="{{route('mimin.pengaturan.provinsi.kabupaten.index',[$provinsi->id])}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>
</div>
</div>
                <!-- Bordered table end -->
@endsection
