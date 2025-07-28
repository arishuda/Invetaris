<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Domain</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div class="modal-body">
                <form action="{{ route('storedomain') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Expired Date</label>
                                <input type="date" class="form-control" id="expired_date" name="expired_date" required>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <label>Registrar</label>
                            <input type="text" class="form-control" id="registrar" name="registrar" required>
                        </div>

                        {{-- <div class="row" --}}
                        <div class="col-sm-4">
                            <label>Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-sm-4">
                            <label>Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-sm-4">
                            <label>Company</label>
                            <input type="text" class="form-control" id="company" name="company" required>
                        </div>
                        <div class="col-sm-4">
                            <label>Remark</label>
                            <input type="text" class="form-control" id="remark" name="remark" required>
                        </div>
                    </div>
                    

                        
                      <br>
                      {{-- <div class="modal-footer justify-content-between"> 
             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
            </div>    --}}
                    <button style="width:100%" type="submit" name="button" class="btn btn-primary">Input</button>
                </form>
            </div>
           
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
