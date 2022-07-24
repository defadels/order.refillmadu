@extends('layouts/contentLayoutMaster')

@section('title', $judul)
@section('judul', $judul)

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
@endsection
@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
@endsection
@section('page-script')
<script src="{{ asset(mix('js/scripts/pages/app-index.js')) }}"></script>
@endsection

@section('content')

<!-- Basic Horizontal form layout section start -->
<section id="basic-horizontal-layouts">
                    <div class="row match-height">
                        <div class="col-md-12 col-12">

                            <div class="card">
                                <div class="card-header">


                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                            <div class="form-body">

                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Nama Produk</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                              <strong>  {{$produk->nama}}</strong>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Kode Produk</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                              <strong>  {{$produk->kode}}</strong>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Kategori</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                              <strong>  {{$produk->kategori->nama}}</strong>
                                                            </div>
                                                        </div>
                                                </div>


                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Harga Jual</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                           Rp <strong>    {{number_format($produk->harga_jual)}} </strong>
                                                            </div>
                                                        </div>
                                                </div>

                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Harga Pokok</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                           Rp <strong>    {{number_format($produk->harga_pokok)}} </strong>
                                                            </div>
                                                        </div>
                                                </div>


                                                    <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                                <span>Deskripsi</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                          <strong>{{$produk->deskripsi}}</strong>

                                                          </div>
                                                    </div>
</div>

                                                    @if(isset($produk->created_at))
                                                    <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                                <span>Diposting</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                          <strong>{{$produk->created_at->format('d-m-Y H:i:s')}}</strong>

                                                       </div>
                                                          </div>
                                                    </div>
                                                    @endif
                                                    @if(isset($produk->updated_at))
                                                    <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                                <span>Diperbaharui</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                          <strong>{{$produk->updated_at->format('d-m-Y H:i:s')}}</strong>

                                                       </div>
                                                          </div>
                                                    </div>
                                                    @endif

                                                    <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                                <span>Cabang</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                          <strong>{{$produk->cabang->nama}}</strong>

                                                       </div>
                                                          </div>
                                                    </div>





                                                    <div class="col-md-8 offset-md-4">
                                                        <a href="{{route('mimin.inventori.produk.index')}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
</section>

<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-header">
                                  <h4 class="card-title mb-2">
                                    Harga Khusus
</h4>
<a style="margin-top: -18px;" class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{route('mimin.inventori.produk.hargakhusus.create',$produk->id)}}"><i class="feather icon-plus"></i></a>

                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Kategori Pelanggan</th>
                                                <th>Harga Satuan</th>
                                                <th width="15%"></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @forelse ($daftar_harga as $harga)
                                            <tr>

                                                <td>
                                                 {{ $harga->kategori->nama}}
                                                </td>

                                                <td class="text-right">
                                                  {{number_format($harga->harga_jual)}}
                                                </td>

                                            <td class="text-center">

                                              <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{route('mimin.inventori.produk.hargakhusus.edit',[$produk->id,$harga->id])}}"><i class="feather icon-edit"></i></a>
                                              <a class="btn btn-icon btn-outline-danger btn-sm waves-effect waves-light confirm-delete" data-nama="Harga untuk {{$harga->kategori->nama}}"  data-aksi="{{route('mimin.inventori.produk.hargakhusus.destroy',[$produk->id,$harga->id])}}" href="javascript:void(0)">
                                                <i class="feather icon-trash" data-nama="Harga untuk {{$harga->kategori->nama}}"  data-aksi="{{route('mimin.inventori.produk.hargakhusus.destroy',[$produk->id,$harga->id])}}"></i></a>
                                                </td>
                                            </tr>

                                        @empty

                                        <tr><td colspan="3">Harga khusus belum ditentukan</td></tr>
                                        @endforelse

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer">

                           </div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->


<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-header">
                                  <h4 class="card-title mb-2">
                                    Struktur Produk
</h4>
<a style="margin-top: -18px;" class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{route('mimin.inventori.produk.struktur.create',$produk->id)}}"><i class="feather icon-plus"></i></a>

                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Bahan</th>
                                                <th>Kuantitas</th>
                                                <th>Satuan</th>
                                                <th width="1%"></th>
                                                <th>Kuantitas</th>
                                                <th>Satuan</th>
                                                <th>Produk</th>
                                                <th width="15%"></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @forelse ($daftar_struktur as $struktur)
                                            <tr>

                                                <td>
                                                 {{$struktur->bahan->nama}}
                                                </td>
                                                <td>
                                                  {{$struktur->qty_bahan}}
                                                </td>
                                                <td>
                                                 {{$struktur->bahan->satuan}}
                                                </td>
                                                <td class="text-center">

                                              <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="javascript:void(0)"><i class="feather icon-arrow-right"></i></a>
                                                </td>
                                                <td>
                                                 {{$struktur->qty_produk}}
                                                </td>
                                                <td>
                                                {{$produk->satuan}}
                                                </td>

                                                <td>
                                                {{$produk->nama}}
                                                </td>

                                                <td class="text-center">

                                                <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{route('mimin.inventori.produk.struktur.edit',[$produk->id,$struktur->id])}}"><i class="feather icon-edit"></i></a>
                                                <a class="btn btn-icon btn-outline-danger btn-sm waves-effect waves-light confirm-delete" data-nama="Bahan {{$struktur->bahan->nama}}"  data-aksi="{{route('mimin.inventori.produk.struktur.destroy',[$produk->id,$struktur->id])}}" href="javascript:void(0)">
                                                  <i class="feather icon-trash" data-nama="Bahahan {{$struktur->bahan->nama}}"  data-aksi="{{route('mimin.inventori.produk.struktur.destroy',[$produk->id,$struktur->id])}}"></i></a>
                                                  </td>
                                                </tr>
                                            @empty

                                        <tr><td colspan="8">Struktur produk tidak ditentukan</td></tr>
                                        @endforelse

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer">

                           </div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->



@endsection
