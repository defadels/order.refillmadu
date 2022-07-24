@extends('layouts/contentLayoutMaster')

@section('title', $title)
@section('judul', $judul)


@section('page-style')
<link rel="stylesheet" href="{{ asset(mix('/vendors/css/wysiwyg/summernote-lite.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('/vendors/css/wysiwyg/summernote-lite.custom.css')) }}">
@endsection

@section('page-script')
<script src="{{ asset(mix('/vendors/js/wysiwyg/summernote/summernote-lite.min.js')) }}"></script>

<script>
$(document).ready( function() {


  $('.editor').summernote( { tabsize: 5,   height: 200});

        function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
            $('#foto').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
        }

        $("#account-upload").change(function() {
        readURL(this);
        });

        function bacaGambar(input,target) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
            $(target).attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
        }

        $("#gambar1-upload").change(function() {
            bacaGambar(this, '#gambar_pengeluaran');
        });
        $("#gambar-besar-upload").change(function() {
            bacaGambar(this, '#gambar_pengeluaran_besar');
        });

        $("#gambar_flyer_file").change(function() {
            bacaGambar(this, '#img_flyer');
        });
        $("#gambar_poster_file").change(function() {
            bacaGambar(this, '#img_poster');
        });
});

</script>
@endsection

@section('content')

<!-- Basic Horizontal form layout section start -->
<section id="basic-horizontal-layouts">


                    <div class="row">
                        <div class="col-md-8 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Formulir untuk Mengubah Pengeluaran</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        {!! Form::model($pengeluaran,['method'=>'patch','route' => ['mimin.pengeluaran.update',$pengeluaran->id],'class'=>'form form-horizontal','enctype'=>'multipart/form-data']) !!}
                                            <div class="form-body">

                                                <div class="row">

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <span>Icon</span>
                                                            </div>
                                                            <div class="col-md-10">
                                                            <div class="media">
                                                                <a class="mr-2 my-25" href="#">
                                                                    <img id="foto" src="{{$pengeluaran->logo_foto}}" alt="users avatar" class="users-avatar-shadow rounded" width="90" height="90" style="object-fit: cover;">
                                                                </a>
                                                                <div class="media-body mt-50">
                                                                    <h4 class="media-heading">90 x 90 px</h4>
                                                                    <div class="col-12 d-flex mt-1 px-0">
                                                                        <label class="btn btn-primary d-none d-sm-block mr-75 cursor-pointer" for="account-upload">Ganti Icon</label>
                                                                        <label class="btn btn-primary d-block d-sm-none mr-75 cursor-pointer" for="account-upload"><i class="feather icon-edit-1"></i></label>
                                                                        <input type="file" id="account-upload" name="icon_img" hidden>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <span>Kategori</span>
                                                            </div>
                                                            <div class="col-md-10">

                                                            {{  Form::select('kategori_pengeluaran_id', $daftar_kategori, old('kategori_pengeluaran_id'), ['class' => 'form-control']) }}
                                                             </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <span>Nama</span>
                                                            </div>
                                                            <div class="col-md-10">

                                                            {{ Form::text('nama',old('nama'), ['class' => 'form-control','required']) }}
                                                             </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <span>Singkatan</span>
                                                            </div>
                                                            <div class="col-md-10">

                                                            {{ Form::text('singkatan',old('singkatan'), ['class' => 'form-control']) }}
                                                             </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <span>Deskripsi</span>
                                                            </div>
                                                            <div class="col-md-10">

                                                            {{ Form::text('deskripsi',old('deskripsi'), ['class' => 'form-control']) }}
                                                             </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <span>Latar Belakang</span>
                                                            </div>
                                                            <div class="col-md-10">

                                                            {{ Form::textarea('latar_belakang',old('latar_belakang'), ['class' => 'form-control editor','rows'=>"3"]) }}
                                                             </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <span>Tujuan</span>
                                                            </div>
                                                            <div class="col-md-10">

                                                            {{ Form::textarea('tujuan',old('tujuan'), ['class' => 'form-control editor','rows'=>"3"]) }}
                                                             </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <span>Output</span>
                                                            </div>
                                                            <div class="col-md-10">

                                                            {{ Form::textarea('output',old('output'), ['class' => 'form-control editor','rows'=>"3"]) }}
                                                             </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Dipublikasikan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            <ul class="list-unstyled mb-0">
                                                            <li class="d-inline-block mr-2">
                                                                <fieldset>
                                                                    <div class="vs-radio-con">

                                                                    @if($pengeluaran->publikasi == "ya")
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

                                                                    @if($pengeluaran->publikasi == "tidak")
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


                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Tampilkan di Halaman Depan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            <ul class="list-unstyled mb-0">
                                                            <li class="d-inline-block mr-2">
                                                                <fieldset>
                                                                    <div class="vs-radio-con">

                                                                    @if($pengeluaran->halaman_depan == "ya")
                                                                        <input type="radio" name="halaman_depan" checked value="ya">
                                                                    @else

                                                                    <input type="radio" name="halaman_depan" value="ya">
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
                                                                        @if($pengeluaran->halaman_depan == "tidak")
                                                                        <input type="radio" name="halaman_depan" checked value="tidak">
                                                                        @else

                                                                        <input type="radio" name="halaman_depan" value="tidak">
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


                                                    <div class="col-md-8 offset-md-2">
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1">Simpan</button>
                                                        <a href="{{route('mimin.pengeluaran.index')}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>


                                                    </div>
                                                </div>
                                            </div>
                                            {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                          <!-- start card -->
                            <div class="card">
                                <div class="card-header mb-1">
                                    <h4 class="card-title">Gambar | 848 x 420 px</h4>
                                </div>
                                <div class="card-content" style="text-align:center;">
                                    <img id="gambar_pengeluaran" src="{{$pengeluaran->gambar_foto}}" alt="Card image cap" width="{{848*38/100}}px" height="{{420*38/100}}px"  style="object-fit: cover;">

                                </div>
                                <div class="card-footer text-muted">
                                {!! Form::open(['route' => ['mimin.pengeluaran.update_foto',$pengeluaran->id],'class'=>'form form-horizontal','enctype'=>'multipart/form-data']) !!}

                                    <span class="float-left">
                                    <button type="submit" class="btn btn-primary mr-1">Simpan</button>

                                    </span>
                                    <span class="float-right">
                                    <label class="btn btn-outline-warning" for="gambar1-upload">Ganti</label>
                                    <input type="file" id="gambar1-upload" name="gambar" hidden>
                                     </span>

                                     {!! Form::close() !!}

                                </div>
                            </div>
                            <!-- end cart-->

                              <!-- start cart-->

                              <div class="card">
                                <div class="card-header mb-1">
                                    <h4 class="card-title">Flyer | 1080 x 1080 px</h4>
                                </div>
                                <div class="card-content" style="text-align:center;">
                                    <img id="img_flyer" src="{{$pengeluaran->thumbnail_sedang}}" alt="Card image cap" width="250px" height="250px"  style="object-fit: cover;">

                                </div>
                                <div class="card-footer text-muted">
                                {!! Form::open(['route' => ['mimin.pengeluaran.update_foto',$pengeluaran->id],'class'=>'form form-horizontal','enctype'=>'multipart/form-data']) !!}

                                    <span class="float-left">
                                    <button type="submit" class="btn btn-primary mr-1">Simpan</button>

                                    </span>
                                    <span class="float-right">
                                    <label class="btn btn-outline-warning" for="gambar_flyer_file">Ganti</label>
                                    <input type="file" id="gambar_flyer_file" name="gambar_flyer_file" hidden>
                                     </span>

                                     {!! Form::close() !!}

                                </div>
                            </div>

                            <!-- end card-->
                            <!-- start cart-->

                            <div class="card">
                                <div class="card-header mb-1">
                                    <h4 class="card-title">Poster A4 | 1240 x 1754 px</h4>
                                </div>
                                <div class="card-content" style="text-align:center;">
                                    <img id="img_poster" src="{{$pengeluaran->thumbnail_besar}}" alt="Card image cap" width="210px" height="297px"  style="object-fit: cover;">

                                </div>
                                <div class="card-footer text-muted">
                                {!! Form::open(['route' => ['mimin.pengeluaran.update_foto',$pengeluaran->id],'class'=>'form form-horizontal','enctype'=>'multipart/form-data']) !!}

                                    <span class="float-left">
                                    <button type="submit" class="btn btn-primary mr-1">Simpan</button>

                                    </span>
                                    <span class="float-right">
                                    <label class="btn btn-outline-warning" for="gambar_poster_file">Ganti</label>
                                    <input type="file" id="gambar_poster_file" name="gambar_poster_file" hidden>
                                     </span>

                                     {!! Form::close() !!}

                                </div>
                            </div>

                            <!-- end card-->



                        </div>



                    </div>
</section>

@endsection
