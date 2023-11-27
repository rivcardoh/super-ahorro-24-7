@extends('layouts.admin')
@section('contenido')
<div class="col-md-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Nuevo Ingreso</h3>
        </div>

        <form action="{{route('ingreso.store')}}" method="POST" class="form">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="proveedor">Proveedor</label>
                            <select name="id_proveedor" class="form-control" id="id_proveedor">
                                @foreach($personas as $persona)
                                <option value="{{$persona->id_persona}}">{{$persona->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="tipo_comprobante">Tipo comprobante</label>
                            <input type="text" class="form-control" name="tipo_comprobante" id="ci" placeholder="Ingrese el CI">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="num_comprobante">Numero comprobante</label>
                            <input type="text" class="form-control" name="num_comprobante" id="num_comprobante" placeholder="Ingrese la direccion">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="telefono">Telefono</label>
                            <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Ingrese el telefono">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Ingrese el email">
                    </div>

                </div>
                <div class="card-footer">
                    <div>
                        <button type="submit" class="btn btn-success me-1 mb-1">Guardar</button>
                        <button type="reset" class="btn btn-danger me-1 mb-1">Cancelar</button>
                    </div>
                </div>

            </div>

        </form>
    </div>
</div>
@endsection