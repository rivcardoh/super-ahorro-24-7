@extends('layouts.admin')
@section('contenido')
<div class="col-md-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Nueva Categoria</h3>
        </div>

        <form action="{{route('categoria.store')}}" method="POST" class="form">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="categoria">Nombre</label>
                    <input type="text" class="form-control" name="categoria" id="categoria" placeholder="Ingrese el nombre de la categoria">
                </div>
                <div class="form-group">
                    <label for="categoria">Descripcion</label>
                    <input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Ingrese la descripcion">
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