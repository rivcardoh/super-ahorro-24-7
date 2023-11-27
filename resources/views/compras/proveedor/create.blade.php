@extends('layouts.admin')
@section('contenido')
<div class="col-md-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Nuevo Proveedor</h3>
        </div>

        <form action="{{route('proveedor.store')}}" method="POST" class="form">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="cliente">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese el nombre del proveedor">
                </div>
                <div class="form-group">
                    <label for="ci">CI</label>
                    <input type="text" class="form-control" name="ci" id="ci" placeholder="Ingrese el CI">
                </div>
                <div class="form-group">
                    <label for="direccion">Direccion</label>
                    <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Ingrese la direccion">
                </div>
                <div class="form-group">
                    <label for="telefono">Telefono</label>
                    <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Ingrese el telefono">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email" id="email" placeholder="Ingrese el email">
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