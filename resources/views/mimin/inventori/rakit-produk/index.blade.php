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
<a href="{{route('mimin.inventori.rakit-produk.create')}}" class="btn-icon btn btn-outline-warning btn-round waves-effect waves-light" aria-haspopup="true" aria-expanded="false"><i class="feather icon-plus"></i> Tambah</a>

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
                                                <th class="text-right">Jumlah</th>
                                                <th class="text-center">Gudang</th>
                                                <th>Petugas</th>
                                                <th class="text-center" width="1%">Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($daftar_rakit_produk as $rakit_produk)
                                            <tr>

                                                <td>
                                                    {{$rakit_produk->created_at->format('d-m-Y')}}
                                                </td>
                                                <td>
                                                    {{$rakit_produk->produk->nama}}
                                                </td>
                                                <td class="text-right">
                                                    {{number_format($rakit_produk->stok_hasil)}}
                                                </td>
                                                    <td class="text-center">
                                                    {{$rakit_produk->gudang->nama}}
                                                </td>
                                                <td>
                                                    {{$rakit_produk->dibuat_oleh->nama}}
                                                </td>
                                                <td class="text-center">
                                                <div class="badge badge-{{$rakit_produk->status=='Posted'?'primary':'danger'}} badge-md">

                                                {{$rakit_produk->status}}
                                                </div>

                                                </td>

                                            <td  style="text-align:center;">
                                            @if($rakit_produk->status == "Draft")

                                            <a class="btn btn-icon btn-outline-primary btn-sm waves-effect waves-light" href="{{ route('mimin.inventori.rakit-produk.post',$rakit_produk->id) }}"><i class="feather icon-save"></i></a>
                                             <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{ route('mimin.inventori.rakit-produk.edit',$rakit_produk->id) }}"><i class="feather icon-edit"></i></a>
                                              <a class="btn btn-icon btn-outline-danger btn-sm waves-effect waves-light confirm-delete" data-nama="Rakit {{$rakit_produk->produk->nama}}"  data-aksi="{{route('mimin.inventori.rakit-produk.destroy',$rakit_produk->id)}}" href="javascript:void(0)">
                                                <i class="feather icon-trash" data-nama="Rakit {{$rakit_produk->produk->nama}}"  data-aksi="{{route('mimin.inventori.rakit-produk.destroy',$rakit_produk->id)}}"></i></a>
                                            @else

                                            <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{ route('mimin.inventori.rakit-produk.show',$rakit_produk->id) }}"><i class="feather icon-list"></i></a>
                                            @endif
                                              </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer">

                            {{ $daftar_rakit_produk->appends(request()->input())->links() }}
                           </div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
