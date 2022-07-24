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
<a href="{{route('mimin.inventori.retur-barang.create')}}" class="btn-icon btn btn-outline-warning btn-round waves-effect waves-light" aria-haspopup="true" aria-expanded="false"><i class="feather icon-plus"></i> Tambah</a>

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
                                        @foreach ($daftar_retur_barang as $retur_barang)
                                            <tr>

                                                <td>
                                                    {{$retur_barang->created_at->format('d-m-Y')}}
                                                </td>
                                                <td>
                                                    {{Str::limit($retur_barang->daftar_detil->pluck('produk.nama')->implode(", "),50)}}
                                                </td>

                                                <td class="text-center">
                                                      {{$retur_barang->gudang->nama}}
                                                </td>
                                                <td>
                                                    {{$retur_barang->dibuat_oleh->nama}}
                                                </td>
                                                <td class="text-center">
                                                <div class="badge badge-{{$retur_barang->status=='Posted'?'primary':'danger'}} badge-md">

                                                {{$retur_barang->status}}
                                                </div>

                                                </td>

                                            <td  style="text-align:center;">
                                            @if($retur_barang->status == "Draft")

                                            <a class="btn btn-icon btn-outline-primary btn-sm waves-effect waves-light" href="{{ route('mimin.inventori.retur-barang.post',$retur_barang->id) }}"><i class="feather icon-save"></i></a>
                                             <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{ route('mimin.inventori.retur-barang.edit',$retur_barang->id) }}"><i class="feather icon-edit"></i></a>
                                              <a class="btn btn-icon btn-outline-danger btn-sm waves-effect waves-light confirm-delete" data-nama="Retur Barang tanggal {{$retur_barang->created_at->format('d-m-Y')}}"  data-aksi="{{route('mimin.inventori.retur-barang.destroy',$retur_barang->id)}}" href="javascript:void(0)">
                                                <i class="feather icon-trash" data-nama="Retur Barang tanggal {{$retur_barang->created_at->format('d-m-Y')}}"  data-aksi="{{route('mimin.inventori.retur-barang.destroy',$retur_barang->id)}}"></i></a>
                                            @else

                                            <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{ route('mimin.inventori.retur-barang.show',$retur_barang->id) }}"><i class="feather icon-list"></i></a>
                                            @endif
                                              </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer">

                            {{ $daftar_retur_barang->appends(request()->input())->links() }}
                           </div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
