<div class="modal fade" id="edit-{{$data->id}}">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Stok Barang</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>


        <div class="modal-body">


        <form action="{{ route('updatedomain', $data->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
            <label>Lokasi Barang</label>
            <input type="text" class="form-control" name="lokasi" value="{{$data->name}}">
            </div>
            <div class="form-group">
                <label>Lokasi Barang</label>
                <input type="text" class="form-control" name="lokasi" value="{{$data->city}}">
                </div>


            <button type="submit" name="button" class="btn btn-success">Update</button>
          </form>



        </div>

      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>