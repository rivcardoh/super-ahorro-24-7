@extends ('layouts.admin')
@section ('contenido')
<div div class="col-md-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Detalle Ingreso</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="id_proveedor">Proveedor</label>
                        <p>
                            {{$ingreso->nombre}}
                        </p>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="tipo_comprobante">Tipo comprobante</label>
                        <p>
                            {{$ingreso->tipo_comprobante}}
                        </p>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="num_comprobante">Numero comprobante</label>
                        <p>
                            {{$ingreso->num_comprobante}}
                        </p>
                    </div>
                </div>
                <div class="col 12">
                    <div class="table-responsive">
                        <table id="detalles" class="table table-hover mb-0">
                            <thead style="background-color:#A9D0F5">
                                <tr>

                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio compra</th>
                                    <th>Precio venta</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tfoot>

                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total:</th>
                                <th>
                                    <h4 id="total">$ {{number_format($ingreso->total,2)}}</h4>
                                </th>
                            </tfoot>
                            <tbody>
                                @foreach($detalles as $det)
                                <tr>
                                    <td>{{$det->producto}}</td>
                                    <td>{{$det->cantidad}}</td>
                                    <td>{{$det->precio_compra}}</td>
                                    <td>{{$det->precio_venta}}</td>
                                    <td>{{number_format($det->cantidad*$det->precio_compra, 2)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    <!-- table hover -->

                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->

</div>
<!-- Content Header (Page header) -->

<!-- /.content-header -->


<!-- Hoverable rows end -->

@endsection