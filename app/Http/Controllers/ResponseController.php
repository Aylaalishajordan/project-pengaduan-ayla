<?php

namespace App\Http\Controllers;

use App\Models\Response;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function show(Response $response)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function edit($report_id)
    {
        //ambil data response yang bakal dimunculin, data yang diambil data response yang report_id nya sama kaya $repor_id dari patch dinamis (rport_id)
        //kalau ada, datanya diambil satu / first ()
        //kenapa ga pake fistOrfaill() karena nanti bakal munculin not found view, kalau pakai first () view nya tetep bakal ditampilin
        $report = Response::where('report_id', $report_id)->first();
        //karena mau kirim data (report_id) buat diroute updatenya, jadi biar bisa dipakai diblade kita simpan data patch dinamis $report_id nya ke variable baru yang bakal dicompact dan dipanggil blade nya
        $reportId = $report_id;
        return view('response', compact('report', 'reportId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $report_id)
    {
        $request->validate([
            'status'=> 'required',
            'pesan'=> 'required',
        ]);
        // updateOrCreate() fungsinya untuk melakukan update data kalo emang di respoonnya udah ada data yang report_id sama dengan $report_id dari patch dinamis, kalau ga ada data itu maka dicreate
        //array pertama, acuan dari datanya
        //array kedua, data yang dikirim
        //kenapa pake updateOrCreate? karena response ini kan tadinya ga mau ditambahin tapi kalo ada mau diupdate aja
        Response::updateOrCreate(
            [
                'report_id' => $report_id,
            ],
            [
                'status' => $request->status,
                'pesan' => $request->pesan,
            ]
            );
            //setelah berhasil, arahkan keroute yang name nya data.petugas dengan pesan alert
            return redirect()->route('data.petugas')->with('responseSuccess', 'Berhasil mengubah response!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function destroy(Response $response)
    {
        //
    }
}
