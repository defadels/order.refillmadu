@extends('layouts/contentLayoutMaster')

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
    placeholder: 'Cari dan Pilih Pelanggan...',
    ajax: {
      url: '{{route('mimin.pesanan.preorder.cari-pelanggan')}}',
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

    templateSelection: formatRepoSelection
 });

 function formatRepoSelection (repo) {


    $('#reseller_id').val(repo.parent_nama);

    $('#segment').val(repo.kategori_nama);
    $('#distributor').val(repo.distributor_nama);

  return repo.text;
 }
 </script>
@endsection

@section('content')

<!-- Basic Horizontal form layout section start -->
<section id="basic-horizontal-layouts">
                    <div class="row match-height">
                        <div class="col-md-12 col-12">
                            <div class="card">
                            <div class="card-header">
                                    <h4 class="card-title">Formulir Pemesanan</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">



                                    {!! Form::open(['method' => 'GET','route' => ['mimin.pesanan.preorder.create2'],'class'=>'form form-horizontal']) !!}
                                    <div class="form-body">
                                                <div class="row">


                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Diproses Oleh Cabang</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            {{ Form::select('cabang',$daftar_cabang,old('cabang'), ['class' => 'form-control select2']) }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Pelanggan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::select('pelanggan',[],old('pelanggan'), ['class' => 'form-control caripelanggan']) }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 reseller_form">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Segmen</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::text('segment',old('segment'), ['class' => 'form-control','disabled'=>'disabled','id'=>'segment']) }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 reseller_form">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Leader
                                                                </span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::text('reseller_id',old('reseller_id'), ['class' => 'form-control','disabled'=>'disabled','id'=>'reseller_id']) }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 reseller_form">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Distributor</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::text('distributor',old('distributor'), ['class' => 'form-control','disabled'=>'disabled','id'=>'distributor']) }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 offset-md-4">
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1">Bikin Pesanan</button>
                                                        <a href="{{route('mimin.pesanan.preorder.index')}}" class="btn btn-outline-warning mr-1 mb-1">Batal</a>
                                                    </div>

                                                </div>
                                            </div>
                                        {!!Form::close()!!}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
</section>

@endsection
