<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Buku::orderBy('judul', 'asc')->get();
        return response()->json([
            'status' => true,
            'message' => 'Data ditemukan',
            'data' => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'judul' => 'required',
            'pengarang' => 'required',
            'tanggal_publikasi' => 'required|date'
        ],[
            'judul.required' => 'Judul wajib diisi',
            'pengarang.required' => 'Pengarang wajib diisi',
            'tanggal_publikasi.required' => 'Tanggal publikasi wajib diisi',
            'tanggal_publikasi.date' => 'Format Tanggal publikasi tidak sesuai',
        ]);

        if($validation->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Data gagal ditambahkan',
                'errors' => $validation->errors()
            ], 400);
        }
        $data = [
            'judul' => $request->judul,
            'pengarang' => $request->pengarang,
            'tanggal_publikasi' => $request->tanggal_publikasi
        ];

        Buku::create($data);
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil ditambahkan',
            'data' => $data
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Buku::find($id);
        if($data){
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $buku = Buku::find($id);
        if(empty($buku)){
            return response()->json([
                'status' => false,
                'message' => 'Data buku tidak ditemukan',
            ], 404);
        }

        $validation = Validator::make($request->all(), [
            'judul' => 'required',
            'pengarang' => 'required',
            'tanggal_publikasi' => 'required|date'
        ],[
            'judul.required' => 'Judul wajib diisi',
            'pengarang.required' => 'Pengarang wajib diisi',
            'tanggal_publikasi.required' => 'Tanggal publikasi wajib diisi',
            'tanggal_publikasi.date' => 'Format Tanggal publikasi tidak sesuai',
        ]);

        if($validation->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Data gagal diupdate',
                'errors' => $validation->errors()
            ], 400);
        }
        $data = [
            'judul' => $request->judul,
            'pengarang' => $request->pengarang,
            'tanggal_publikasi' => $request->tanggal_publikasi
        ];

        $buku->update($data);
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate',
            'data' => $data
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $buku = Buku::find($id);
        if(empty($buku)){
            return response()->json([
                'status' => false,
                'message' => 'Data buku tidak ditemukan',
            ], 404);
        }

        $buku->delete();
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}
