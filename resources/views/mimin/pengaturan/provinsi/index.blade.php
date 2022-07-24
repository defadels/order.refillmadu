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
<a href="{{route('mimin.pengaturan.provinsi.create')}}" class="btn-icon btn btn-outline-warning btn-round waves-effect waves-light" aria-haspopup="true" aria-expanded="false"><i class="feather icon-plus"></i> Tambah</a>

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
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($daftar_provinsi as $provinsi)
                                            <tr>

                                                <td>
                                                    {{$provinsi->nama}}

                                                </td>
                                                <td>
                                                    {{$provinsi->status}}

                                                </td>

                                            <td  style="text-align:center;">

                                            <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light"
                                                href="{{ route('mimin.pengaturan.provinsi.kabupaten.index',$provinsi->id) }}">
                                                <i class="feather icon-list"></i>  Kabupaten</a>


                                              <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{ route('mimin.pengaturan.provinsi.edit',$provinsi->id) }}"><i class="feather icon-edit"></i></a>
                                              <a class="btn btn-icon btn-outline-danger btn-sm waves-effect waves-light confirm-delete" data-nama="{{$provinsi->nama}}"  data-aksi="{{route('mimin.pengaturan.provinsi.destroy',$provinsi->id)}}" href="javascript:void(0)">
                                                <i class="feather icon-trash" data-nama="{{$provinsi->nama}}"  data-aksi="{{route('mimin.pengaturan.provinsi.destroy',$provinsi->id)}}"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer">

                            {{ $daftar_provinsi->appends(request()->input())->links() }}
                           </div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
