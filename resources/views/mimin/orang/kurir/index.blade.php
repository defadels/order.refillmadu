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
<a href="{{route('mimin.orang.kurir.create')}}" class="btn-icon btn btn-outline-warning btn-round waves-effect waves-light" aria-haspopup="true" aria-expanded="false"><i class="feather icon-plus"></i> Tambah</a>

@endsection
@section('content')
<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                {!! Form::open(['method'=>'get','route' => ['mimin.orang.kurir.index'],'class'=>'form form-inline input-group']) !!}

<div class="input-group-prepend">
<span class="input-group-text" id="basic-addon1"><i class="feather icon-search"></i></span>
</div>
{!!Form::text('cari',$cari,['class'=>'form-control','id'=>'cari','placeholder'=>'Cari Nama / Nomor Hp / ID Kurir'])!!}
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
                                                <th>Email</th>
                                                <th>Nomor Hp</th>
                                                <th>Cabang</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($daftar_kurir as $kurir)
                                            <tr>

                                                <td>
                                                    {{$kurir->nama}}

                                                </td>
                                                <td>{{$kurir->email}}</td>
                                                <td>{{$kurir->nomor_hp}}</td>
                                                <td>{{$kurir->cabang->nama}}</td>

                                            <td  style="text-align:center;">

                                              <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{ route('mimin.orang.kurir.edit',$kurir->id) }}"><i class="feather icon-edit"></i></a>


                                               <a class="btn btn-icon btn-outline-danger btn-sm waves-effect waves-light confirm-delete" data-nama="{{$kurir->nama}}"  data-aksi="{{route('mimin.orang.kurir.destroy',$kurir->id)}}" href="javascript:void(0)"> <i class="feather icon-trash" data-nama="{{$kurir->nama}}"  data-aksi="{{route('mimin.orang.kurir.destroy',$kurir->id)}}"></i></a>




                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer">

                            {{ $daftar_kurir->appends(request()->input())->links() }}
</div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
