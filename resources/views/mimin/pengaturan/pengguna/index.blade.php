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
<a href="{{route('mimin.pengaturan.pengguna.create')}}" class="btn-icon btn btn-outline-warning btn-round waves-effect waves-light" aria-haspopup="true" aria-expanded="false"><i class="feather icon-plus"></i> Tambah</a>

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
                                                <th>Email</th>
                                                <th>Nomor Hp</th>
                                                <th>Hak Akses</th>
                                                <th>Cabang</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($daftar_admin as $admin)
                                            <tr>

                                                <td>
                                                    {{$admin->nama}}

                                                </td>
                                                <td>{{$admin->email}}</td>
                                                <td>{{$admin->nomor_hp}}</td>

                                                <td>{{$admin->roles->first()->name}}</td>
                                                <td>{{$admin->cabang->nama}}</td>

                                            <td  style="text-align:center;">

                                              <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{ route('mimin.pengaturan.pengguna.edit',$admin->id) }}"><i class="feather icon-edit"></i></a>


                                               <a class="btn btn-icon btn-outline-danger btn-sm waves-effect waves-light confirm-delete" data-nama="{{$admin->nama}}"  data-aksi="{{route('mimin.pengaturan.pengguna.destroy',$admin->id)}}" href="javascript:void(0)"> <i class="feather icon-trash" data-nama="{{$admin->nama}}"  data-aksi="{{route('mimin.pengaturan.pengguna.destroy',$admin->id)}}"></i></a>




                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer">

                            {{ $daftar_admin->appends(request()->input())->links() }}
</div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
