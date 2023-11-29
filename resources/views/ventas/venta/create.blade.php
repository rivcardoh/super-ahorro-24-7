@extends('layouts.admin')
@section('contenido')
<div class="col-md-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Nueva Venta</h3>
        </div>

        <form action="{{route('venta.store')}}" method="POST" class="form">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="id_cliente">Cliente</label>
                            <select name="id_cliente" class="form-control" id="id_cliente">
                                @foreach($personas as $persona)
                                <option value="{{$persona->id_persona}}">{{$persona->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="tipo_comprobante">Tipo comprobante</label>
                            <input type="text" class="form-control" name="tipo_comprobante" id="ci" placeholder="">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="num_comprobante">Numero comprobante</label>
                            <input type="text" class="form-control" name="num_comprobante" id="num_comprobante" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <label for="id_producto">Productos</label>
                        <select name="pidarticulo" class="form-control selectpicker" id="pidarticulo" data-live-search="true">
                            @foreach($productos as $producto)
                            <option value="{{$producto->id_producto}}_{{$producto->stock}}_{{$producto->precio_promedio}}">{{$producto->Articulo}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" class="form-control" name="pcantidad" id="pcantidad" placeholder="Ingrese la cantidad">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label for="pstock">Stock</label>
                            <input type="number" disabled class="form-control" name="pstock" id="pstock" step="0.01" min="0" placeholder="STOCK">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label for="pventa">Precio Venta</label>
                            <input type="text" disabled class="form-control" name="pprecio_venta" id="pprecio_venta" step="0.01" min="0" placeholder="P. venta">
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="form-group">
                            <label for="descuento">Descuento</label>
                            <input type="number" class="form-control" value="0" name="descuento" id="descuento" step="0.01" min="0" placeholder="Descuento">
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="form-group">
                            <label for="accion">Accion</label>
                            <button type="button" id="btn_add" class="btn btn-block btn-outline-success">Agregar</button>
                        </div>
                    </div>
                </div>

                <!-- table hover -->
                <div class="table-responsive">
                    <table id="detalles" class="table table-hover mb-0">
                        <thead style="background-color:#A9D0F5">
                            <tr>
                                <th>Opciones</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio venta</th>
                                <th>Descuento</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <th>Total</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>
                                <h4 id="total">$ 0.00</h4><input type="hidden" name="total_venta" id="total_venta">
                            </th>
                        </tfoot>
                        <tbody>

                        </tbody>
                    </table>

                </div>
            </div>
            <div class="form-control">
                <div class="card-footer">
                    <button type="submit" id="guardar" class="btn btn-success me-1 mb-1">Guardar</button>
                    <button type="reset" class="btn btn-danger me-1 mb-1">Cancelar</button>
                </div>
            </div>

        </form>
    </div>


</div>
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('#btn_add').click(function() {
            agregar();
        });
    });

    $('#btn_guardar').click(function() {
        swal({
            title: 'Su cambio es!',
            text: 'Gracias por su compra',
            type: 'succes'
        })
    });

    var cont = 0;
    total = 0;
    subtotal = [];

    $("#guardar").hide();
    $("#pidarticulo").change(mostrarValores);

    function mostrarValores() {
        datosArticulo = document.getElementById('pidarticulo').value.split('_');
        $('#pstock').val(datosArticulo[1]);
        $('#pprecio_venta').val(datosArticulo[2]);

    }

    function agregar() {
        datosArticulo = document.getElementById('pidarticulo').value.split('_');

        idarticulo = datosArticulo[0];
        articulo = $("#pidarticulo option:selected").text();
        cantidad = parseInt($("#pcantidad").val());
        descuento = $("#descuento").val();
        precio_venta = $("#pprecio_venta").val();
        stock = parseInt($("#pstock").val());
        unidad = datosArticulo[3];

        if (idarticulo != "" && cantidad != "" && cantidad > 0 && descuento != "" && precio_venta != "") {
            if (cantidad < stock) {
                subtotal[cont] =  ( cantidad *precio_venta ) - descuento;
                total = total + subtotal[cont];

                var fila = '<tr class="selected" id="fila' + cont +
                    '"><td><button type="button" class="btn btn-warning" onclick="eliminar(' + cont +
                    ')";>X</button></td><td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + idarticulo +
                    '</td><td><input type="number" name="cantidad[]" value="' + cantidad +
                    '"></td> <td><input type="number" name="precio_venta[]" value="' + precio_venta +
                    '"></td> <td> <input type="number" name="descuento[]" value="' + descuento +
                    '"</td><td>' + subtotal[cont] + '</td></tr>';

                cont++;
                limpiar();
                $("#total").html("$" + total);
                $("#total_venta").val(total);
                evaluar();
                $("#detalles").append(fila);
            }
            else{
                alert('La cantidad a vender supera el stock');
            }

        } else {
            alert("Error al ingresar el detalle del ingreso, revise los datos del articulo");
        }
    }

    function limpiar() {
        $("#pcantidad").val("");
        $("#pdescuento").val("");
        $("#pprecio_venta").val("");
        $("#pstock").val("");
    }

    function evaluar() {
        if (total > 0) {
            $("#guardar").show();
        } else {
            $("#guardar").hide();
        }
    }

    function eliminar(index) {
        total = total - subtotal[index];
        $("#total").html("$: " + total);
        $("#fila" + index).remove();
        evaluar();
    }
</script>
@endpush

@endsection