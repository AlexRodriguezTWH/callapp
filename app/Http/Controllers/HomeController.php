<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EnVivo;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $llamadas = EnVivo::whereNull('PuntoVenta')->whereNull('fechacierre')->orderBy('Fechatms', 'Desc')->paginate(10);
        #dd($llamadas);

        return view('home', compact('llamadas'));
    }

    public function pendientes()
    {
        $llamadas = EnVivo::whereNotNull('PuntoVenta')->whereNULL('fechacierre')->orderBy('Fechatms', 'Desc')->paginate(10);
        #dd($llamadas);

        return view('home', compact('llamadas'));
    }

    public function historial()
    {
        $llamadas = EnVivo::whereNotNull('PuntoVenta')->whereNotNULL('fechacierre')->orderBy('Fechatms', 'Desc')->paginate(10);
        #dd($llamadas);

        return view('home', compact('llamadas'));
    }
}
