@extends('layouts/contentLayoutMaster')

@section('title', $title)
@section('judul', $judul)

@section('tombol_sudut')
<a href="{{route('mimin.pengeluaran.create')}}" class="btn-icon btn btn-primary btn-round" aria-haspopup="true" aria-expanded="false"><i class="feather icon-plus"></i>Tambah</a>

<a href="{{route('mimin.pengeluaran.kategori.index')}}" class="btn-icon btn btn-success btn-round" aria-haspopup="true" aria-expanded="false"><i class="feather icon-tag"></i>Kategori</a>

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
                                                <th>Tanggal</th>
                                                <th>Nominal</th>
                                                <th>Kategori</th>
                                                <th>Keterangan</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @forelse ($daftar_pengeluaran as $pengeluaran)
                                            <tr>

                                                <td>{{$pengeluaran->tanggal->format('d-m-Y')}}</td>
                                                <td>{{number_format($pengeluaran->nominal)}}</td>
                                                <td>{{$pengeluaran->kategori->nama}}</td>
                                                <td>{{$pengeluaran->keterangan}}</td>
                                                <td  style="text-align:center;">
                                                  <a class="btn btn-icon btn-outline-primary btn-sm waves-effect waves-light" href="{{route('mimin.pengeluaran.show',$pengeluaran->id)}}"><i class="feather icon-list"></i></a>
                                                  <a class="btn btn-icon btn-outline-success btn-sm waves-effect waves-light" href="{{route('mimin.pengeluaran.show',$pengeluaran->id)}}"><i class="feather icon-printer"></i></a>

                                                </td>
                                            </tr>
                                        @empty
                                        <tr>

                                        <td colspan="5"> Belum ada Pengeluaran</td>
                                        </tr>
                                        @endforelse

                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <div class="card-footer">

                            {{ $daftar_pengeluaran->appends(request()->input())->links() }}

                          </div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
