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
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        {!! Form::open(['route' => ['mimin.inventori.produk.struktur.store',$produk->id],'class'=>'form form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="row">


                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Bahan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::select('bahan_id',$daftar_bahan,old('bahan_id'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Kuantitas Bahan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::number('qty_bahan',old('qty_bahan'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Kuantitas Produk</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::number('qty_produk',old('qty_produk'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>




                                                    <div class="col-md-8 offset-md-4">
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1">Tambah</button>
                                                        <a href="{{route('mimin.inventori.produk.show',$produk->id)}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

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
