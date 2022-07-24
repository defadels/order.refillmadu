@extends('layouts/contentLayoutMaster')

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
                                    <h4 class="card-title">Formulir Perakitan Produk</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        {!! Form::open(['route' => ['mimin.inventori.rakit-produk.store'],'class'=>'form form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="row">


                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Produk yang Dibuat</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::select('produk_id',$daftar_produk,old('produk_id'), ['class' => 'form-control']) }}
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
                                                                <span>Jumlah yang dihasilkan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::number('stok_hasil',old('stok_hasil'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Gudang</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::select('gudang_id',$daftar_gudang,old('gudang_id'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-8 offset-md-4">
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1">Tambah</button>
                                                        <a href="{{route('mimin.inventori.rakit-produk.index')}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

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
