<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Ingreso;
use App\Models\DetalleIngreso;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\IngresoFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;

class IngresoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request) {
            $query = trim($request->get('texto'));
            $ingresos = DB::table('ingreso as i')
                ->join('persona as p', 'i.id_proveedor', '=', 'id_persona')
                ->join('detalle_ingreso as di', 'di.id_ingreso', '=', 'i.id_ingreso')
                ->select('i.id_ingreso', 'i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado', DB::raw('sum(di.cantidad*di.precio_compra)as total'))
                ->where('i.num_comprobante', 'LIKE', "%" . $query . '%')
                ->groupBy('i.id_ingreso', 'i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado')
                ->orderBy('i.id_ingreso', 'desc')
                ->paginate(15);
            return view('compras.ingreso.index', ["ingresos" => $ingresos, "texto" => $query]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $personas = DB::table('persona')->where('tipo_persona', '=', 'Proveedor')->where('estado', '=', '1')->get();
        $ingreso = Ingreso::all();
        $productos = DB::table('productos as p')
            ->select(DB::raw('CONCAT(p.codigo, " ", p.nombre, " ", p.stock)AS Articulo'), 'p.id_producto', 'p.stock')
            ->where('p.estado', '=', 'Activo')
            ->get();
        return view("compras.ingreso.create", ["personas" => $personas, "productos" => $productos, "ingreso" => $ingreso]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
        
            // Guardar en la tabla Ingreso
            $ingreso = new Ingreso();
            $ingreso->id_proveedor = $request->get('id_proveedor');
            $ingreso->tipo_comprobante = $request->get('tipo_comprobante');
            $ingreso->num_comprobante = $request->get('num_comprobante');
            $ingreso->fecha_hora = Carbon::now('America/La_Paz')->toDateTimeString();
            $ingreso->impuesto = '13';
            $ingreso->num_comprobante = $request->get('num_comprobante');
            $ingreso->estado = 'A';
            $ingreso->save();
        
            // Guardar en la tabla DetalleIngreso
            $detalles = [];
            $id_producto = $request->get('idarticulo');
            $cantidades = $request->get('cantidad');
            $precios_compra = $request->get('precio_compra');
            $precios_venta = $request->get('precio_venta');
        
            foreach ($id_producto as $index => $id) {
                $detalle = new DetalleIngreso();
                $detalle->id_ingreso = $ingreso->id_ingreso;
                $detalle->id_producto = $id;
                $detalle->cantidad = $cantidades[$index];
                $detalle->precio_compra = $precios_compra[$index];
                $detalle->precio_venta = $precios_venta[$index];
                $detalle->save();
                $detalles[] = $detalle;
            }
        
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Manejar la excepción, por ejemplo, puedes loguear el error o devolver un mensaje de error al usuario.
            return response()->json(['error' => 'Error al procesar la solicitud.'], 500);
        }
        return Redirect::to('compras/ingreso');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ingreso = DB::table('ingreso as i')
            ->join('persona as p', 'i.id_proveedor', '=', 'p.id_persona')
            ->join('detalle_ingreso as di', 'i.id_ingreso', '=', 'di.id_ingreso')
            ->select('i.id_ingreso', 'i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado', DB::raw('sum(di.cantidad*di.precio_compra) as total'))
            ->where('i.id_ingreso', '=', $id)
            ->groupBy('i.id_ingreso', 'i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado')
            ->orderBy('i.id_ingreso', 'desc')
            ->first();

        $detalles = DB::table('detalle_ingreso as d')
            ->join('productos as p', 'd.id_producto', '=', 'p.id_producto')
            ->select('p.nombre as producto', 'd.cantidad', 'd.precio_compra', 'd.precio_venta')
            ->where('d.id_ingreso', '=', $id)
            ->get();
        return view("compras.ingreso.show", ["ingreso" => $ingreso, "detalles" => $detalles]);
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
        $ingreso = Ingreso::findOrFail($id);
        $ingreso->estado = 'C';
        $ingreso->update();
        return Redirect::to('compras/ingreso');
    }
}
