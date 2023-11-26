<div class="modal fade" id="modal-delete-{{$cli->id_persona}}">
    <div class="modal-dialog">
        <form action="{{route('cliente.destroy' , $cli->id_persona)}}" method="POST"> 
        @csrf
        @method('DELETE')
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar cliente</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Deseas eliminar este cliente {{$cli->nombre}}</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-outline-light">Eliminar</button>
            </div>
        </div>
        </form>
    </div>
</div>