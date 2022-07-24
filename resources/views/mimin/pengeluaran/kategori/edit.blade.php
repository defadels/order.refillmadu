@extends('layouts/contentLayoutMaster')

@section('title', $title)
@section('judul', $judul)

@section('dropdown')
@endsection
@section('content')

<!-- Basic Horizontal form layout section start -->
<section id="basic-horizontal-layouts">
                    <div class="row match-height">
                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Formulir untuk Mengubah Kategori Pengeluaran</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        {!! Form::model($kategori_pengeluaran,['method'=>'patch','route' => ['mimin.pengeluaran.kategori.update',$kategori_pengeluaran->id],'class'=>'form form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="row">



                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Nama</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                            {{ Form::text('nama',old('nama'), ['class' => 'form-control','required']) }}
                                                             </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Deskripsi</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                            {{ Form::text('deskripsi',old('deskripsi'), ['class' => 'form-control']) }}
                                                             </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Publikasi</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            <ul class="list-unstyled mb-0">
                                                            <li class="d-inline-block mr-2">
                                                                <fieldset>
                                                                    <div class="vs-radio-con">

                                                                    @if($kategori_pengeluaran->publikasi == "ya")
                                                                        <input type="radio" name="publikasi" checked value="ya">
                                                                    @else
                                                                        <input type="radio" name="publikasi" value="ya">

                                                                    @endif
                                                                        <span class="vs-radio">
                                                                            <span class="vs-radio--border"></span>
                                                                            <span class="vs-radio--circle"></span>
                                                                        </span>
                                                                        Ya
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                            <li class="d-inline-block mr-2">
                                                                <fieldset>
                                                                    <div class="vs-radio-con">

                                                                    @if($kategori_pengeluaran->publikasi == "tidak")
                                                                        <input type="radio" name="publikasi" checked value="tidak">
                                                                    @else
                                                                        <input type="radio" name="publikasi" value="tidak">

                                                                    @endif
                                                                        <span class="vs-radio">
                                                                            <span class="vs-radio--border"></span>
                                                                            <span class="vs-radio--circle"></span>
                                                                        </span>
                                                                        Tidak
                                                                    </div>
                                                                </fieldset>
                                                            </li>

                                                        </ul>
                                                            </div>
                                                        </div>
                                                    </div>



                                                    <div class="col-md-8 offset-md-4">
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1">Simpan</button>
                                                        <a href="{{route('mimin.pengeluaran.kategori.index')}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

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
