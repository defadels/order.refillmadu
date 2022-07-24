@extends('layouts/contentLayoutMaster')

@section('title', $title)
@section('judul', $judul)


@section('tombol_sudut')
<a href="{{route('mimin.pengeluaran.kategori.create')}}" class="btn-icon btn btn-primary btn-round" aria-haspopup="true" aria-expanded="false"><i class="feather icon-plus"></i>Tambah</a>
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
                                                <th>Deskripsi</th>
                                                <th>Published</th>
                                                @can('kategoripengeluaran-edit')
                                                <th></th>
                                                @endcan
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($daftar_kategori_pengeluaran as $pengeluaran)
                                            <tr>


                                                <td>{{$pengeluaran->nama}}</td>
                                                <td>{{$pengeluaran->deskripsi}}</td>
                                                <td>{{$pengeluaran->publikasi}}</td>
                                                <td  style="text-align:center;">
                                            <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{route('mimin.pengeluaran.kategori.edit',$pengeluaran->id)}}"><i class="feather icon-edit"></i></a>

                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>


                            </div>
<div class="card-footer">
                          {{ $daftar_kategori_pengeluaran->appends(request()->input())->links() }}
</div>
                        </div>

                        <a href="{{route('mimin.pengeluaran.index')}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
