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
<a href="{{route('mimin.orang.pelanggan.alamat.create',$pelanggan->id)}}" class="btn-icon btn btn-outline-warning btn-round waves-effect waves-light" aria-haspopup="true" aria-expanded="false"><i class="feather icon-plus"></i> Tambah</a>

@endsection
@section('content')
<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                  <table>
                                    <tr>
                                        <td>Nama</td>
                                        <td>: {{$pelanggan->nama}}</td>
                                    </tr>
                                    @if($pelanggan->level == 2)
                                    <tr>
                                        <td>Leader</td>
                                        <td>: {{$pelanggan->parent->nama}} [{{$pelanggan->parent->kategori->nama}}]</td>
                                    </tr>

                                    @elseif($pelanggan->level == 3)
                                    <tr>
                                        <td>Leader</td>
                                        <td>: {{$pelanggan->parent->nama}} [{{$pelanggan->parent->kategori->nama}}]</td>
                                    </tr>
                                    <tr>
                                        <td>Distributor</td>
                                        <td>: {{$pelanggan->parent->parent->nama}}  [{{$pelanggan->parent->parent->kategori->nama}}]</td>
                                    </tr>


                                    @elseif($pelanggan->level == 4)
                                    <tr>
                                        <td>Leader</td>
                                        <td>: {{$pelanggan->parent->nama}} [{{$pelanggan->parent->kategori->nama}}]</td>
                                    </tr>
                                    <tr>
                                        <td>Leader Leader</td>
                                        <td>: {{$pelanggan->parent->parent->nama}}  [{{$pelanggan->parent->parent->kategori->nama}}]</td>
                                    </tr>
                                    <tr>
                                        <td>Distributor</td>
                                        <td>: {{$pelanggan->parent->parent->parent->nama}}  [{{$pelanggan->parent->parent->parent->kategori->nama}}]</td>
                                    </tr>

                                    @endif


                                  </table>

                                 </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Label</th>
                                                <th>Nama</th>
                                                <th>Nomor Hp</th>
                                                <th>Alamat</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($daftar_alamat as $alamat)
                                            <tr>
                                                <td>
                                                    {{$alamat->label}}
                                                </td>
                                                <td>
                                                    {{$alamat->nama}}
                                                </td>
                                                <td>{{$alamat->nomor_hp}}</td>
                                                <td>{{$alamat->alamat}}</td>
                                                <td  style="text-align:center;">
                                                  <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{ route('mimin.orang.pelanggan.alamat.edit',[$pelanggan->id,$alamat->id]) }}"><i class="feather icon-edit"></i></a>
                                                  <a class="btn btn-icon btn-outline-danger btn-sm waves-effect waves-light confirm-delete" data-nama="{{$alamat->label}}"  data-aksi="{{route('mimin.orang.pelanggan.alamat.destroy',[$pelanggan->id,$alamat->id])}}" href="javascript:void(0)"> <i class="feather icon-trash" data-nama="{{$alamat->label}}"  data-aksi="{{route('mimin.orang.pelanggan.alamat.destroy',[$pelanggan->id,$alamat->id])}}"></i></a>
                                               </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer">

                            {{ $daftar_alamat->appends(request()->input())->links() }}
</div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
