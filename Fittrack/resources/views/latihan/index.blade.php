@extends('template.main')
@section('title', 'Latihan')
@section('content')
<link rel="stylesheet" href="{{ mix('css/latihan.css') }}">

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">@yield('title')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="text-right">
                                <a href="{{ route('latihan.create') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-plus"></i> Tambah Latihan
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- Session Messages -->
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <table id="example1" class="table table-striped table-bordered table-hover text-center" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Latihan</th>
                                        <th>Gambar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latihans as $latihan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $latihan->nama_latihan }}</td>
                                        <td>
                                            @if($latihan->gambar_latihan)
                                                <!-- Tampilkan gambar menggunakan Base64 -->
                                                <img src="data:image/jpeg;base64,{{ $latihan->gambar_latihan }}" alt="Latihan Image" width="50">
                                            @else
                                                <span class="text-muted">Tidak Ada</span>
                                            @endif
                                        </td>                                        
                                        <td>
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('latihan.edit', $latihan->id_latihan) }}" class="btn btn-success btn-sm mr-1">
                                                <i class="fa-solid fa-pen"></i> Edit
                                            </a>

                                            <!-- Tombol Delete -->
                                            <form action="{{ route('latihan.destroy', $latihan->id_latihan) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                                    <i class="fa-solid fa-trash-can"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
</div>

@endsection
