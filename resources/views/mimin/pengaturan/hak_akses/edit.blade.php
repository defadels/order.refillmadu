@extends('layouts/contentLayoutMaster')

@section('title', $judul)


@section ('tombol_sudut')

@endsection
@section('content')


<section class="vuexy-checkbox-color">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Hak Akses</h4>
                                </div>

                                <div class="card-content">



                                {!! Form::model($role, ['method' => 'PUT','route' => ['mimin.pengaturan.hakakses.update', $role->id]]) !!}

                                <div class="card-body">
                                            <div class="col-12">
                                                <div class="text-bold-600 font-medium-2 mb-1">
                                                   Nama
                                                </div>
                                                <fieldset class="form-group position-relative has-icon-left">
                                                    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control col-12 col-md-6')) !!}
                                                </fieldset>
                                            </div>
                                            <div class="col-12">
                                                <div class="text-bold-600 font-medium-2 mb-1">
                                                    Permissions
                                                </div>
                                                <ul class="list-unstyled mb-0">
                                            @foreach($permission as $value)
                                            <li class="d-inline-block mr-2">
                                                <fieldset>
                                                    <div class="vs-checkbox-con vs-checkbox-primary">
                                                    {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                                        <span class="vs-checkbox">
                                                            <span class="vs-checkbox--check">
                                                                <i class="vs-icon feather icon-check"></i>
                                                            </span>
                                                        </span>
                                                        <span class="">{{ $value->name }}</span>
                                                    </div>
                                                </fieldset>
                                            </li>
                                            @endforeach

                                        </ul>
                                            </div>


                                        <div class="col-12">
</br>
<hr>
                                      <a href="{{route('mimin.pengaturan.hakakses.index')}}" class="btn-icon btn btn-outline-warning waves-effect waves-light" aria-haspopup="true" aria-expanded="false"><i class="fa fa-chevron-left"></i> Kembali</a>

                                              <button type="submit" class="btn btn-primary">Submit</button>
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

