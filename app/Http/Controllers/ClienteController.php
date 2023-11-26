<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ClienteFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        //
        if ($request) {
            $query = trim($request->get('texto'));
            $clientes = DB::table('persona')->where('nombre', 'LIKE', '%' . $query . '%')
                ->where('tipo_persona', '=', 'Cliente')
                ->where('estado', '=', '1')
                ->orderBy('id_persona', 'desc')
                ->paginate(7);
            return view('ventas.cliente.index', ["cliente" => $clientes, "texto" => $query]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("ventas.cliente.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClienteFormRequest $request)
    {
        $cliente=new Cliente();
        $cliente->tipo_persona='Cliente';
        $cliente->nombre=$request->input('nombre');
        $cliente->ci=$request->input('ci');
        $cliente->direccion=$request->input('direccion');
        $cliente->telefono=$request->input('telefono');
        $cliente->email=$request->input('email');
        $cliente->estado='1';
        $cliente->save();
        return Redirect::to('ventas/cliente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return view("ventas.cliente.show",["cliente"=>Cliente::findOrFail($id)]);
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view("ventas.cliente.edit", ["cliente" =>Cliente::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClienteFormRequest $request, $id)
    {
        $cliente=Cliente::findOrFail($id);
        $cliente->nombre=$request->input('nombre');
        $cliente->ci=$request->input('ci');
        $cliente->direccion=$request->input('direccion');
        $cliente->telefono=$request->input('telefono');
        $cliente->email=$request->input('email');
        $cliente->update();
        return Redirect::to('ventas/cliente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cliente=Cliente::findOrFail($id);
        $cliente->estado='0';
        $cliente->update();
        /* return Redirect::to('almacen/cliente'); */
        return redirect()->route('cliente.index')
                    ->with('success', 'Cliete eliminado correctamente');
    }
}
