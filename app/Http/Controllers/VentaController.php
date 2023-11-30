<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\VentaFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;

use Response;
use Illuminate\Support\Collection;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        if ($request) {
            $query = trim($request->get('texto'));
            $ventas = DB::table('venta as v')
                ->join('persona as p', 'v.id_cliente', '=', 'id_persona')
                ->join('detalle_venta as dv', 'dv.id_venta', '=', 'v.id_venta')
                ->select('v.id_venta', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.num_comprobante', 'v.impuesto', 'v.estado', 'v.total_venta')
                ->where('v.num_comprobante', 'LIKE', "%" . $query . '%')
                ->groupBy('v.id_venta', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.num_comprobante', 'v.impuesto', 'v.estado', 'v.total_venta')
                ->orderBy('v.id_venta', 'desc')
                ->paginate(10);
            return view('ventas.venta.index', ["ventas" => $ventas, "texto" => $query]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $personas = DB::table('persona')->where('tipo_persona', '=', 'Cliente')->get();
        $productos = DB::table('productos as p')
            ->join('detalle_ingreso as di', 'di.id_producto', '=', 'p.id_producto')
            ->select(DB::raw('CONCAT(p.codigo, " ", p.nombre, " ", p.stock)AS Articulo'), 'p.id_producto', 'p.stock', DB::raw('avg(di.precio_venta)as precio_promedio'))
            ->where('p.estado', '=', 'Activo')
            ->where('p.stock', '>', '0')
            ->groupBy('Articulo', 'p.id_producto', 'p.stock')
            ->get();
        return view("ventas.venta.create", ["personas" => $personas, "productos" => $productos]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $ventas = new Venta();
            $ventas->id_cliente = $request->get('id_cliente');
            $ventas->tipo_comprobante = $request->get('tipo_comprobante');
            $ventas->num_comprobante = $request->get('num_comprobante');
            $ventas->fecha_hora = Carbon::now('America/La_Paz')->toDateTimeString();
            $ventas->impuesto = '13';
            $ventas->total_venta = $request->get('total_venta');
            $ventas->estado = 'A';
            $ventas->save();
            
           /*  $detalles = [];
            $id_producto = $request->get('idarticulo');
            $cantidades = $request->get('cantidad');
            $descuentos = $request->get('descuento');
            $precios_venta = $request->get('precio_venta');
        
            foreach ($id_producto as $index => $id) {
                $detalle = new DetalleVenta();
                $detalle->id_venta = $ventas->id_venta;
                $detalle->id_producto = $id;
                $detalle->cantidad = $cantidades[$index];
                $detalle->precio_compra = $descuentos[$index];
                $detalle->precio_venta = $precios_venta[$index];
                $detalle->save();
                $detalles[] = $detalle;
            } */
            $id_producto = $request->get('idarticulo');
            $cantidad = $request->get('cantidad');
            $descuento = $request->get('descuento');
            $precio_venta = $request->get('precio_venta');

            $cont=0;
            while($cont <count($id_producto)){
                $detalle = new DetalleVenta();
                $detalle->id_venta = $ventas->id_venta;
                $detalle->id_producto = $id_producto[$cont];
                $detalle->cantidad = $cantidad[$cont];
                $detalle->descuento = $descuento[$cont];
                $detalle->precio_venta = $precio_venta[$cont];
                $detalle->save();
                $cont=$cont+1;
            }
            DB::commit();
        }  catch (\Exception $e) {
            DB::rollBack();
        }
        return Redirect::to('ventas/venta');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $ventas = DB::table('venta as v')
            ->join('persona as p', 'v.id_cliente', '=', 'p.id_persona')
            ->join('detalle_venta as dv', 'v.id_venta', '=', 'dv.id_venta')
            ->select('v.id_venta', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.num_comprobante', 'v.impuesto', 'v.estado', 'v.total_venta' )
            ->where('v.id_venta', '=', $id)
            ->first();

        $detalles = DB::table('detalle_venta as d')
            ->join('productos as p', 'd.id_producto', '=', 'p.id_producto')
            ->select('p.nombre as producto', 'd.cantidad', 'd.descuento', 'd.precio_venta')
            ->where('d.id_venta', '=', $id)
            ->get();
        return view("ventas.venta.show", ["ventas" => $ventas, "detalles" => $detalles]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ventas = Venta::findOrFail($id);
        $ventas->estado = 'C';
        $ventas->update();
        return Redirect::to('ventas/venta');
    }
}
