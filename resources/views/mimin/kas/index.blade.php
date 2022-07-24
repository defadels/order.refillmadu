@extends('layouts/contentLayoutMaster')

@section('title', $title)
@section('judul', $judul)

@section('tombol_sudut')

<a href="{{route('mimin.kas.create')}}" class="btn-icon btn btn-primary btn-round" aria-haspopup="true" aria-expanded="false"><i class="feather icon-plus"></i>Tambah</a>

<a href="{{route('mimin.kas.transfer.index')}}" class="btn-icon btn btn-success btn-round" aria-haspopup="true" aria-expanded="false"><i class="feather icon-list"></i>Transfer</a>

@endsection

@section('content')
<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>keterangan</th>
                                                <th>Saldo</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($daftar_kas as $kas)
                                            <tr>
                                                <td>{{$kas->nama}}
                                              </td>
                                                <td>{{$kas->keterangan}}</td>
                                                <td style="text-align:right">{{number_format($kas->saldo)}}</td>
                                                <td style="text-align:center;">

                                                <a class="btn btn-icon btn-outline-success btn-sm waves-effect waves-light" href="{{route('mimin.kas.show',$kas->id)}}"><i class="feather icon-list"></i> Transaksi</a>

                                                <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{route('mimin.kas.transfer.create',['asal'=>$kas->id])}}"><i class="feather icon-repeat"></i> Transfer</a>

                                                <a class="btn btn-icon btn-outline-danger btn-sm waves-effect waves-light" href="{{route('mimin.kas.edit',$kas->id)}}"><i class="feather icon-edit"></i></a>

                                              </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">

                          </div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
