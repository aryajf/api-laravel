<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $current_url = url()->current();
        $client = new Client();
        $url = env('API_URL').'/buku';
        if($request->input('page') !== ''){
            $url .= '?page='.$request->input('page');
        }
        $response = $client->get($url);
        $content = $response->getBody()->getContents();
        $contentObj = json_decode($content, false);
        $data = $contentObj->data;
        foreach ($data->links as $key => $value) {
            $data->links[$key]->url2 = str_replace(env('API_URL'), env('BASE_URL'), $value->url);
        }
        return view('buku.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $body = [
            'judul' => $request->judul,
            'pengarang' => $request->pengarang,
            'tanggal_publikasi' => $request->tanggal_publikasi,
        ];

        try{
            $client = new Client();
            $url = env('API_URL').'/buku';
            $response = $client->post($url, [
                'headers' => ['Content-type' => 'application/json'],
                'body' => json_encode($body)
            ]);
            $content = $response->getBody()->getContents();
            $contentObj = json_decode($content, false);
            $message = $contentObj->message;
            return redirect()->to('buku')->with('success', $message);
        }catch(RequestException $e) {
            if ($e->hasResponse()){
                $content = $e->getResponse()->getBody()->getContents();
                $contentObj = json_decode($content, false);
                $errors = $contentObj->errors;
                return redirect()->to('buku')->withErrors($errors)->withInput();
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try{
            $client = new Client();
            $url = env('API_URL').'/buku/'.$id;
            $response = $client->get($url);
            $content = $response->getBody()->getContents();
            $contentObj = json_decode($content, false);
            $data = $contentObj->data;
            return view('buku.index', compact('data'));
        }catch(RequestException $e) {
            if ($e->hasResponse()){
                $content = $e->getResponse()->getBody()->getContents();
                $contentObj = json_decode($content, false);
                $errors = $contentObj->message;
                return redirect()->to('buku')->withErrors($errors);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $body = [
            'judul' => $request->judul,
            'pengarang' => $request->pengarang,
            'tanggal_publikasi' => $request->tanggal_publikasi,
        ];

        try{
            $client = new Client();
            $url = env('API_URL').'/buku/'.$id;
            $response = $client->put($url, [
                'headers' => ['Content-type' => 'application/json'],
                'body' => json_encode($body)
            ]);
            $content = $response->getBody()->getContents();
            $contentObj = json_decode($content, false);
            $message = $contentObj->message;
            return redirect()->to('buku')->with('success', $message);
        }catch(RequestException $e) {
            if ($e->hasResponse()){
                $content = $e->getResponse()->getBody()->getContents();
                $contentObj = json_decode($content, false);
                $errors = $contentObj->errors;
                return redirect()->to('buku')->withErrors($errors)->withInput();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $client = new Client();
            $url = env('API_URL').'/buku/'.$id;
            $response = $client->delete($url, [
                'headers' => ['Content-type' => 'application/json']
            ]);
            $content = $response->getBody()->getContents();
            $contentObj = json_decode($content, false);
            $message = $contentObj->message;
            return redirect()->to('buku')->with('success', $message);
        }catch(RequestException $e) {
            if ($e->hasResponse()){
                $content = $e->getResponse()->getBody()->getContents();
                $contentObj = json_decode($content, false);
                $errors = $contentObj->message;
                return redirect()->to('buku')->withErrors($errors);
            }
        }
    }
}
