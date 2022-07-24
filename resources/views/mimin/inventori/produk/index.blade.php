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
<a href="{{route('mimin.inventori.produk.create')}}" class="btn-icon btn btn-outline-warning btn-round waves-effect waves-light" aria-haspopup="true" aria-expanded="false"><i class="feather icon-plus"></i> Tambah</a>
<a href="{{route('mimin.inventori.produk.kategori.index')}}" class="btn-icon btn btn-outline-primary btn-round waves-effect waves-light" aria-haspopup="true" aria-expanded="false"><i class="feather icon-tag"></i> Kategori</a>

@endsection
@section('content')
<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">

{!! Form::open(['method'=>'get','route' => ['mimin.inventori.produk.index'],'class'=>'form form-inline input-group']) !!}

<div class="input-group-prepend">
<span class="input-group-text" id="basic-addon1"><i class="feather icon-search"></i></span>
</div>
{!!Form::text('cari',$cari,['class'=>'form-control','id'=>'cari'])!!}

{!!Form::select('kategori',$daftar_kategori,$kategori,['class'=>'form-control col-sm-3','id'=>'kategori'])!!}
&nbsp;
&nbsp;
  <button type="submit" class="btn btn-primary">Cari</button>

{!!Form::close()!!}
                                 </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>

                                                <th>Nama</th>
                                                <th>Kategori</th>
                                                <th class="text-right">Harga</th>
                                                <th class="text-right">Stok</th>
                                                <th class="text-right">Poin</th>
                                                <th width="15%"></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($daftar_produk as $produk)
                                            <tr>

                                                <td>
                                                    {{$produk->nama}}
                                                </td>

                                                <td>
                                                    {{$produk->kategori->nama}}
                                                </td>
                                                <td class="text-right">
                                              {{number_format($produk->harga_jual)}}
                                              </td>
                                                <td class="text-right">
                                                    {{number_format($produk->stok)}}
                                                </td>
                                                <td class="text-right">
                                                    {{number_format($produk->poin)}}
                                                </td>

                                            <td  style="text-align:center;">

                                            <a class="btn btn-icon btn-outline-success btn-sm waves-effect waves-light" href="{{ route('mimin.inventori.produk.show',$produk->id) }}"><i class="feather icon-list"></i></a>
                                              <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{ route('mimin.inventori.produk.edit',$produk->id) }}"><i class="feather icon-edit"></i></a>
                                              <a class="btn btn-icon btn-outline-danger btn-sm waves-effect waves-light confirm-delete" data-nama="{{$produk->nama}}"  data-aksi="{{route('mimin.inventori.produk.destroy',$produk->id)}}" href="javascript:void(0)">
                                                <i class="feather icon-trash" data-nama="{{$produk->nama}}"  data-aksi="{{route('mimin.inventori.produk.destroy',$produk->id)}}"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer">

                            {{ $daftar_produk->appends(request()->input())->links() }}
                           </div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
