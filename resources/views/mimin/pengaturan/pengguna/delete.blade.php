@extends('layouts/contentLayoutMaster')

@section('title', $judul)


@section ('tombol_sudut')

<a href="{{route('mimin.pengaturan.pengguna.create')}}" class="btn-icon btn btn-outline-warning btn-round waves-effect waves-light" aria-haspopup="true" aria-expanded="false"><i class="feather icon-plus"></i> Tambah</a>

@endsection
@section('content')
<!-- Basic Horizontal form layout section start -->
<section id="basic-horizontal-layouts">
                    <div class="row match-height">
                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Yakin mau menghapus {{$admin->nama}} dari Daftar Pengurus</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        {!! Form::model($admin, ['method' => 'DELETE','route' => ['mimin.pengaturan.pengguna.destroy', $admin->id],'class'=>'form form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Nama Lengkap</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ $admin->nama }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Email</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            {{ $admin->email}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Nomor Hp</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                            {{ $admin->nomor_hp }}
                                                             </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Hak Akses</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                            {{  $admin->roles->first()->name }}
                                                             </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-8 offset-md-4">
                                                        <button type="submit" class="btn btn-danger mr-1 mb-1">Hapus</button>
                                                        <a href="{{route('mimin.pengaturan.pengguna.index')}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

                                                    </div>
                                                </div>
                                            </div>
                                            {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
</section>

@endsection
