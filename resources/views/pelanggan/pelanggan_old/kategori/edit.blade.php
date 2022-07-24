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
                                    <h4 class="card-title">Formulir Edit Kategori</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        {!! Form::model($kategori, ['method' => 'PUT','route' => ['pelanggan.pelanggan.kategori.update', $kategori->id],'class'=>'form form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Nama</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::text('nama',old('nama'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Keterangan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::text('keterangan',old('keterangan'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Status</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            <ul class="list-unstyled mb-0">
                                                            <li class="d-inline-block mr-2">
                                                                <fieldset>
                                                                    <div class="vs-radio-con">
                                                                        <input type="radio" name="status" {{$kategori->status == 'Aktif'?'checked':''}} value="Aktif">
                                                                        <span class="vs-radio">
                                                                            <span class="vs-radio--border"></span>
                                                                            <span class="vs-radio--circle"></span>
                                                                        </span>
                                                                        Aktif
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                            <li class="d-inline-block mr-2">
                                                                <fieldset>
                                                                    <div class="vs-radio-con">
                                                                        <input type="radio" name="status" {{$kategori->status == 'Nonaktif'?'checked':''}} value="Nonaktif">

                                                                        <span class="vs-radio">
                                                                            <span class="vs-radio--border"></span>
                                                                            <span class="vs-radio--circle"></span>
                                                                        </span>
                                                                        Nonaktif
                                                                    </div>
                                                                </fieldset>
                                                            </li>

                                                        </ul>
                                                            </div>
                                                        </div>
                                                    </div>




                                                    <div class="col-md-8 offset-md-4">
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1">Simpan</button>
                                                        <a href="{{route('pelanggan.pelanggan.kategori.index')}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

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
