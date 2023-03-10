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
    <h2 class="title-table">Laporan Keluhan</h2>
<div style="display: flex; justify-content: center; margin-bottom: 30px">
    <a href="{{route('logout')}}" style="text-align: center">Logout</a> 
    <div style="margin: 0 10px"> | </div>
    <a href="/" style="text-align: center">Home</a>
</div>
<div style="display: flex; justify-content: flex-end; align items: center;">
    <form action="" method="GET">
        @csrf
        <input type="text" name="search" placeholder="cari berdasarkan nama...">
        <a herf="/data" class="fas fa-search" style="margin-left:10px;"></a>
        <!-- <button type="submit" class="btn-login" style="margin-left: 10px; margin-buttom: 10px" >Cari</button> -->
    </form>
    <a href="{{route('data')}}" style="margin-left: 30px; margin-top: 5px;">Refresh</a>
    <a href="{{route('export-pdf')}}" style="margin-right:20px; margin-left: 10px; margin-top: 5px">Cetak PDF</a>
    <a href="{{route('export-excel')}}" style="margin-right:30px; margin-left: -10px; margin-top: 5px">Cetak EXCEL</a>
</div>
<div style="padding: 0 30px">
    <table>
        <thead>
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
             @php
                 $telp = substr_replace($report->no_telp, "62", 0, 1);
             @endphp
             @php
             if ($report->response) {
                $pesanWa = 'Hallo' . $report->nama . '!pengaduan anda di '. $report->response['status'] . '.Berikut pesan ntuk anda :' . $report->response['pesan'];
             }else{
                $pesanWa = '!Belum ada data response!';
             }
             @endphp
                <td><a href="https://wa.me/{{$telp}}?text={{$pesanWa}}" target="_blank">{{$telp}}</a></td>
                <td>{{$report['pengaduan']}}</td>
                <td>
                    <a href="../assets/image/{{$report->foto}}" target="_blank">
                      <img src="{{asset('assets/image/'.$report->foto)}}" width="120">
                    </a>
                </td>
                <td>
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
                <td>
                    <form action="{{ route('destroy', $report->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="margin-button:-15px">Hapus</button>
                    </form> 

                    <div>
                        <form action="{{route('print-pdf', $report->id)}}" method="get"  style="margin-button:-15px">
                            @csrf
                            <button class="submit">Print</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>