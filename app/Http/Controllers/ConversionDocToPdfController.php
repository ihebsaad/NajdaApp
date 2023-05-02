<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use auth;
use DB;

use Breadlesscode\Office\Converter;


class ConversionDocToPdfController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function conversion()
    {

    Converter::file(__DIR__.'/Demande_evasan_nationale_15N0023.doc') // select a file for convertion
    ->setLibreofficeBinaryPath('/usr/bin/libreoffice') // binary to the libreoffice binary
    ->setTemporaryPath(__DIR__.'/temp') // temporary directory for convertion
    ->setTimeout(100) // libreoffice process timeout
    ->save(__DIR__.'/kkk2.pdf'); // save as pdf
       
    }

    public function index()
    {
       
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       

    }


    public function saving(Request $request)
    {
        
    }

    public function updating(Request $request)
    {

      

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }



    public  static function Liste()
    {
      

    }



    public  static function NbrActus()
    {

       
    }


}

