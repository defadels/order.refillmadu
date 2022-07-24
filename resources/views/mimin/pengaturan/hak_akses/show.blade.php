@extends('layouts/contentLayoutMaster')

@section('title', $judul)


@section ('tombol_sudut')

@endsection
@section('content')

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
@endif


<section class="vuexy-checkbox-color">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Hak Akses</h4>
                                </div>

                                <div class="card-content">

                                {!! Form::model($role, ['method' => 'PATCH','route' => ['mimin.pengaturan.hakakses.update', $role->id]]) !!}

                                <div class="card-body">
                                            <div class="col-12">
                                                <div class="text-bold-600 font-medium-2 mb-1">
                                                   Nama
                                                </div>
                                                <fieldset class="form-group position-relative has-icon-left">
                                                    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control col-12 col-md-6', 'disabled'=>'disabled')) !!}
                                                </fieldset>
                                            </div>
                                            <div class="col-12">
                                                <div class="text-bold-600 font-medium-2 mb-1">
                                                    Permissions
                                                </div>
                                                <ul class="list-unstyled mb-0">
                                            @foreach($rolePermissions as $value)
                                            <li class="d-inline-block mr-2">
                                                <fieldset>

                                                        <span class="badge badge-md badge-primary">{{ $value->name }}</span>

                                                </fieldset>
                                            </li>
                                            @endforeach

                                        </ul>
                                            </div>


                                        <div class="col-12">
</br>
<hr>
                                      <a href="{{route('mimin.pengaturan.hakakses.index')}}" class="btn-icon btn btn-outline-warning waves-effect waves-light" aria-haspopup="true" aria-expanded="false"><i class="fa fa-chevron-left"></i> Kembali</a>

                                           </div>
                                    </div>



                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Vuexy Checkbox Color end -->

@endsection

