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
<a href="{{route('mimin.inventori.stok-keluar.create')}}" class="btn-icon btn btn-outline-warning btn-round waves-effect waves-light" aria-haspopup="true" aria-expanded="false"><i class="feather icon-plus"></i> Tambah</a>

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
                                                <th>Tanggal</th>
                                                <th>Produk</th>
                                                <th class="text-center">Gudang</th>
                                                <th>Petugas</th>
                                                <th class="text-center" width="1%">Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($daftar_stok_keluar as $stok_keluar)
                                            <tr>

                                                <td>
                                                    {{$stok_keluar->created_at->format('d-m-Y')}}
                                                </td>
                                                <td>
                                                    {{Str::limit($stok_keluar->daftar_detil->pluck('produk.nama')->implode(", "),50)}}
                                                </td>

                                                <td class="text-center">
                                                      {{$stok_keluar->gudang->nama}}
                                                </td>
                                                <td>
                                                    {{$stok_keluar->dibuat_oleh->nama}}
                                                </td>
                                                <td class="text-center">
                                                <div class="badge badge-{{$stok_keluar->status=='Posted'?'primary':'danger'}} badge-md">

                                                {{$stok_keluar->status}}
                                                </div>

                                                </td>

                                            <td  style="text-align:center;">
                                            @if($stok_keluar->status == "Draft")

                                            <a class="btn btn-icon btn-outline-primary btn-sm waves-effect waves-light" href="{{ route('mimin.inventori.stok-keluar.post',$stok_keluar->id) }}"><i class="feather icon-save"></i></a>
                                             <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{ route('mimin.inventori.stok-keluar.edit',$stok_keluar->id) }}"><i class="feather icon-edit"></i></a>
                                              <a class="btn btn-icon btn-outline-danger btn-sm waves-effect waves-light confirm-delete" data-nama="Stok Keluar tanggal {{$stok_keluar->created_at->format('d-m-Y')}}"  data-aksi="{{route('mimin.inventori.stok-keluar.destroy',$stok_keluar->id)}}" href="javascript:void(0)">
                                                <i class="feather icon-trash" data-nama="Stok Keluar tanggal {{$stok_keluar->created_at->format('d-m-Y')}}"  data-aksi="{{route('mimin.inventori.stok-keluar.destroy',$stok_keluar->id)}}"></i></a>
                                            @else

                                            <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{ route('mimin.inventori.stok-keluar.show',$stok_keluar->id) }}"><i class="feather icon-list"></i></a>
                                            @endif
                                              </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer">

                            {{ $daftar_stok_keluar->appends(request()->input())->links() }}
                           </div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
