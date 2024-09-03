<div class="d-flex flex-column actions">
    <a class="btn btn-default" href="{{route('orders.show',$order->id)}}"><i class="bi bi-eye"></i></a>
    <a class="btn btn-primary my-2" href="{{route('orders.edit',$order->id)}}"><i class="bi bi-pen"></i></a>
    <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>
    <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteLabel">Eliminazione</h5>
                </div>
                <div class="modal-body text-center">
                    <p>Sei sicuro di voler procedere con la eliminazine ?</p>
                    <div class="spinner-border d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                    <button type="button" class="btn btn-danger">Elimina</button>
                </div>
            </div>
        </div>
    </div>
</div>
