<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;
use PDF;
use Excel;
use App\Exports\ReportsExport;
use App\Models\Response;

class ReportController extends Controller
{
    public function exportPDF(){
        
        //ambil data yang akan ditampilkan pada pdf.bisa juga dengan where atau eloquent lainnya dan jangan gunakan pagination
        //jangan lupa konvert data jadi array dengan toArray()
        $data = Report::with('response')->get()->toArray();
        //kirim data yang diambil kepada view yang akan ditampilkan, kirim dengan inisial
        view()->share('reports',$data);
        //panggil view blade yang akan dicetak pdf serta data yang akan digunakan 
        $pdf = PDF::loadView('print', $data)->setPaper('a4', 'landscape');
        //download pdf file dengan nama tertentu
        return $pdf->download('data_pengaduan_keseluruhan.pdf');
    }

    public function printPDF($id)
     {
        $data = Report::with('response')->where('id', $id)->get()->toArray();
        view()->share('reports', $data);
        $pdf = PDF::loadView('print', $data);
        return $pdf->download('data_perbaris.pdf');
     }

     public function exportExcel()
     {
        //namafile yang akan didownload
        //selain .xlsx juga bisa .csv
        $file_name = 'data_keseluruhan_pengaduan.xlsx';

        //memanggil file ReportsExport dan mendownload dengan nama seperti $file_name
        return Excel::download(new ReportsExport, $file_name);
     }

    public function index()
    {
        //ASC ; asscending->terkecil hingga terbesar 1-100/a-z
        //DESC ; descending->terbesar hingga terkecil 100-1/z-a
        $reports = Report::orderBy('created_at', 'DESC')->simplePaginate(2);
        return view('index', compact('reports'));
    }
    
    //ditambahkan request $request karena pada halaman data ada fitur searchnya dan akan mengambil text yang input search
    public function data(Request $request){
        //ambil data yang diinput ke namenya search
        $search = $request->search;
        //where akan mencari data berdasarkan colum data
        //data yang diambil merupakan data yang 'LIKE'(terdapat) teks yang dimasukin keinput search
        //contoh : ngisi input serch dengan 'fem'
        //bakal nyari kedb yang colum namanya ada teks 'fem'nya
        $reports = Report::with('response')->where('nama', 'LIKE', '%'. $search . '%')->orderBy('created_at', 'DESC')->get();
        return view('data', compact('reports'));
    }

    public function dataPetugas(Request $request)
    {
        $search = $request->search;
        //with : ambil relasi (nama fungsi hasOne/hasMany/belongsTo di modelnya), diambil data dari relasi tersebut
        $reports = Report::with('response')->where('nama', 'LIKE', '%'. $search . '%')->orderBy('created_at', 'DESC')->get();
        return view('data_petugas', compact('reports'));
    }

    public function auth(Request $request)
    {
        //validasi
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);
        //ambil data dan simpan divariable
        $user = $request->only('email', 'password');

        //simpan data keauth dengan Auth::Attempt
        //cek proses penyimpanan keauth berhasil atau tidak lewat IF else
        if (Auth::attempt($user)) {
            //nesting if, if bersarang, if dialam if
            //kalau data login udah masuk kefitur auth, dicek lagi pake if-else
            //kalau data auth tersebut rolenya admin maka masuk ke route data 
            //kalau data auth tersebut rolenya petugas maka masuk ke route data.petugas 
            if (Auth::user()->role == 'admin') {
                return redirect()->route('data');
            }elseif(Auth::user()->role == 'petugas') {
                return redirect()->route('data.petugas');
            }
        }else {
            return redirect()->back()->with('gagal', 'Gagal login, coba lagi!!');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validasi
        $request->validate([
            'nik' => 'required',
            'nama' => 'required',
            'no_telp' => 'required|max:13',
            'pengaduan' => 'required',
            'foto' => 'required|image|mimes:jpg,jpeg,png,svg',
        ]);
        //pindah foto kefolder public
        $path = public_path('assets/image/');
        $image = $request->file('foto');
        $imgName = rand() . '.' . $image->extension(); //
        $image->move($path, $imgName);

        //tambah data kedb
        Report::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'no_telp' => $request->no_telp,
            'pengaduan' => $request->pengaduan,
            'foto' => $imgName,
        ]);
        return redirect()->back()->with('succes', 'Berhasil menambah pengaduan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
            //cari data yang dimaksud
            $data = Report::where('id', $id)->firstOrfail();
            //$data isinya-> nik sampai foto dari pengaduan
            //hapus data foto dari folder public
            //nama fotonya diambil dari $data yang diatas terus ngambil dari columm 'foto'
            //bikin variable yang isinya ngarah kefile foto terkait
            //public_path nyari file difolder public yang namanya sama kaya $data bagian foto
            $image = public_path('assets/image/'.$data['foto']);
            //memanggil foto
            unlink($image);
            //untuk mengahpus foto yang dipublik
            $data->delete();
            //menghpus data dari database
            Response::where('report_id', $id)->delete();
            return redirect()->back();
    }
}
