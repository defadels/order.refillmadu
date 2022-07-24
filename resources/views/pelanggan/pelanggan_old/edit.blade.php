@extends('layouts/pelanggan')

@section('title', $judul)


@section ('tombol_sudut')


@endsection
@section('content')

<!-- Basic Horizontal form layout section start -->
<section id="basic-horizontal-layouts">
                    <div class="row match-height">
                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Formulir Edit Pengurus</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        {!! Form::model($pelanggan, ['method' => 'PUT','route' => ['pelanggan.pelanggan.update', $pelanggan->id],'class'=>'form form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Nama Lengkap</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::text('nama',old('nama'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>ID Pelanggan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::text('kode',old('kode'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Email</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            {{ Form::email('email',old('email'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Nomor Hp</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                            {{ Form::text('nomor_hp',old('nomor_hp'), ['class' => 'form-control']) }}
                                                             </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Kategori</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                            {{  Form::select('kategori_id', $daftar_kategori, old('kategori_id')?old('kategori_id'): $pelanggan->kategori->id, ['class' => 'form-control']) }}
                                                             </div>
                                                        </div>
                                                    </div>



                                                    <div class="col-md-8 offset-md-4">
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1">Simpan</button>
                                                        <a href="{{route('pelanggan.pelanggan.index')}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

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
