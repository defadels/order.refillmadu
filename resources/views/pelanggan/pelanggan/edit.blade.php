@extends('layouts/pelanggan')

@section('title', $judul)


@section ('tombol_sudut')


@endsection

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">

@endsection
@section('vendor-script')
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>

@endsection
@section('page-script')
<script>
$('.select2').select2();
$('.caripelanggan').select2({
    placeholder: 'Cari dan Pilih Leader ...',
    allowClear: true,
    ajax: {
      url: '{{route('pelanggan.pelanggan.cari')}}',
      dataType: 'json',
      delay: 250,
      data: function (params) {
          return {
            cari: params.term,
            page: params.page || 1
          };
        },
      processResults: function (data) {
        return {
          results:  $.map(data.results, function (item) {
            return {
              text: item.nama +" ["+item.kode+"]",
              id: item.id,
              parent_id:item.parent_id,
              parent_nama: item.parent.nama,
              kategori_nama: item.kategori.nama,
              distributor_nama : item.distributor.nama,
              level:item.level
            }
          }),
          pagination: data.pagination
        };
      },
      cache: true
    },

 });


 </script>
@endsection
@section('content')

<!-- Basic Horizontal form layout section start -->
<section id="basic-horizontal-layouts">
                    <div class="row match-height">
                    <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-content">
                                  <div class = "card-body">
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
                                        <td>: {{$pelanggan->parent->nama}}  [{{$pelanggan->parent->parent->kategori->nama}}]</td>
                                    </tr>


                                    @elseif($pelanggan->level == 4)
                                    <tr>
                                        <td>Leader</td>
                                        <td>: {{$pelanggan->parent->nama}} [{{$pelanggan->parent->kategori->nama}}]</td>
                                    </tr>
                                    <tr>
                                        <td>Leader Leader</td>
                                        <td>: {{$pelanggan->parent->nama}}  [{{$pelanggan->parent->parent->kategori->nama}}]</td>
                                    </tr>
                                    <tr>
                                        <td>Distributor</td>
                                        <td>: {{$pelanggan->parent->nama}}  [{{$pelanggan->parent->parent->parent->kategori->nama}}]</td>
                                    </tr>

                                    @endif


                                  </table>
</div>
                                </div>
                            </div>
                    </div>
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
                                                                <span>Leader</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::select('leader',$leader_terpilih,old('leader'), ['class' => 'form-control caripelanggan']) }}
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

                                @if(false)
                                                     <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Password</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                            {{ Form::password('password', ['class' => 'form-control']) }}
                                                             </div>
                                                        </div>
                                                     </div>
                                                     <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Ulangi Password</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                            {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
                                                             </div>
                                                        </div>
                                                     </div>

@endif

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
