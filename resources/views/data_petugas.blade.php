<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan Masyarakat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
</head>
<body>
    <h2 class="title-table">Laporan Keluhan (petugas)</h2>
<div style="display: flex; justify-content: center; margin-bottom: 50px">
    <a href="{{route('logout')}}" style="text-align: center">Logout</a> 
    <div style="margin: 0 30px"> | </div>
    <a href="/" style="text-align: center">Home</a>
</div>
<div style="display: flex; justify-content: flex-end; align items: center;">
    <form action="" method="GET">
        @csrf
        <input type="text" name="search" placeholder="cari berdasarkan nama...">
        <a herf="/data" class="fas fa-search" style="margin-left:10px;"></a>
        <!-- <button type="submit" class="btn-login" style="margin-left: 10px; margin-buttom: 10px" >Cari</button> -->
    </form>
    <a href="{{route('data')}}" style="margin-right: 30px; margin-left: 20px">Refresh</a>
</div>
<div style="padding: 0 30px">
    <table>
        <table class="table">
        <thead class="table-dark">
        <tr>
            <th width="5%">No</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Telp</th>
            <th>Pengaduan</th>
            <th>Gambar</th>
            <th>Status Response</th>
            <th>Pesan Response</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
            @php
             $no = 1;
            @endphp
            @foreach ($reports as $report)
            <tr>
                <td>{{$no++}}</td>
                <td>{{$report['nik']}}</td>
                <td>{{$report['nama']}}</td>
                <td>{{$report['no_telp']}}</td>
                <td>{{$report['pengaduan']}}</td>
                <td>
                    <img src="{{asset('assets/image/'.$report->foto)}}" width="120">
                </td>
                <td>
                    {{--cek apakah data report ini sudah memiiki relasi dengan data dari with('response)--}}
                    @if ($report->response)
                    {{--kalau ada hasil relasinya, tampilkan bagian status--}}
                        {{$report->response['status'] }} 
                    @else
                    {{--kalau ga ada tampilkan tanda ini--}}
                        -
                    @endif               
                </td>
                <td>
                    @if ($report->response)
                    {{--kalau ada hasil relasinya, tampilkan bagian pesan--}}
                        {{$report->response['pesan']}}
                    @else
                    {{--kalau ga ada tampilkan tanda ini--}}
                        -
                    @endif
                </td>
                <td style="display: flex; justify-content: center;">
                    {{--kirim data baru kirim keforeach report ke patch dinamis punyanya route response.edit--}}
                    <a href="{{route('response.edit', $report->id)}}" class="back-btn">Send Response</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>