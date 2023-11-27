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
        if($request)
        {
        $query = trim($request->get('texto'));
        $ingresos = DB::table('ingreso as i')
        ->join('persona as p', 'i.id_proveedor','=','id_persona')
        ->join('detalle_ingreso as di', 'di.id_ingreso', '=', 'i.id_ingreso')
        ->select('i.id_ingreso','i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado', DB::raw('sum(di.cantidad*di.precio_compra)as total'))
        ->where('i.num_comprobante', 'LIKE', "%". $query.'%')
        ->groupBy('i.id_ingreso', 'i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado')
        ->orderBy('i.id_ingreso', 'desc')
        ->paginate(15);
        return view('compras.ingreso.index',["ingresos"=>$ingresos,"texto"=>$query]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $personas=DB::table('persona')->where('tipo_persona','=', 'Proveedor')->get();
       $ingreso=Ingreso::all();
        $productos=DB::table('productos as p')
            ->select(DB::raw('CONCAT(p.codigo, " ", p.nombre)AS Articulo'),'p.id_producto', 'p.stock')
            ->where('p.estado', '=', 'Activo')
            ->get();
            return view("compras.ingreso.create",["personas"=>$personas,"productos"=>$productos, "ingreso"=>$ingreso]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $ingreso=new Ingreso;
            $ingreso->id_proveedor=$request->get('id_proveedor');
            $ingreso->tipo_comprobante=$request->get('tipo_comprobante');
            $ingreso->num_comprobante=$request->get('num_comprobante');
            $mytime= Carbon::now('America/La_Paz');
            $ingreso->fecha_hora=$mytime->toDateTimeString();
            $ingreso->impuesto='13';
            $ingreso->estado='A';
            $ingreso->save();

            $id_producto = $request->get('id_producto');
            $cantidad = $request->get('cantidad');
            $precio_compra = $request->get('precio_compra');
            $precio_venta= $request->get('precio_venta');

            $cont = 0;

            while ($cont <count($id_producto)) {
                $detalle = new DetalleIngreso();
                $detalle ->id_ingreso= $ingreso ->id_ingreso;
                $detalle ->id_producto=$id_producto[$cont];
                $detalle ->cantidad = $$cantidad[$cont];
                $detalle ->precio_compra = $precio_compra[$cont];
                $detalle ->precio_venta= $precio_venta[$cont];
                $detalle ->save();
                
                $cont = $cont+1;
            }

            DB::commit();

        } catch (\Exception $e) 
        {
            DB::rollBack();
        }
        return Redirect::to('compras/ingreso');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ingreso=DB::table('ingreso as i')
        ->join('persona as p', 'i.id_proveedor', '=', 'p.id_persona')
        ->join('detalle_ingreso as di', 'i.id_ingreso', '=', 'di.id_ingreso')
        ->select('i.id_ingreso', 'i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado', DB::raw('sum(di.cantidad*di.precio_compra) as total'))
        ->where('i.id_ingreso', '=', $id)
        ->groupBy('i.id_ingreso', 'i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado')
        ->orderBy('i.id_ingreso', 'desc')
        ->first();

        $detalles=DB::table('detalle_ingreso as d')
        ->join('producto as p','d.id_producto', '=', 'p.id_producto')
        ->select('a.nombre as producto', 'd.cantidad', 'd.precio_compra', 'd.precio_venta')
        ->where('d.ingreso', '=', $id)
        ->get();
        return view("compras.ingreso.show",["ingreso"=>$ingreso, "detalles"=>$detalles]);
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
        $ingreso=Ingreso::findOrFail($id);
        $ingreso->estado='C';
        $ingreso->update();
        return Redirect::to('compras/ingreso');
    }
}
