<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Regions</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div class="modal-body">
                <form action="{{ route('RegAdd') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md">
                            <div class="form-group">
                                <label>Nama Wilayah</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Code Wilayah</label>
                                <input type="text" class="form-control" id="code" name="code">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <label>Level Wilayah</label>
                            <select class="form-control" name="level">
                                <option value="">- Pilih -</option>
                                <option value="KEC">Kecamatan</option>
                                <option value="KEL">Kelurahan</option>
                                <option value="KOTA">Kota</option>
                                <option value="DINAS">Dinas</option>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label>Kota Wilayah</label>
                            <select class="form-control" name="city">
                                <option value="">- Pilih -</option>
                                <option value="Dinas">Dinas</option>
                                <option value="Jakarta Pusat">Jakarta Pusat</option>
                                <option value="Jakarta Timur">Jakarta Timur</option>
                                <option value="Jakarta Utara">Jakarta Utara</option>
                                <option value="Jakarta Selatan">Jakarta Selatan</option>
                                <option value="Jakarta Barat">Jakarta Barat</option>
                                <option value="Kepulauan Seribu">Kepulauan Seribu</option>
                            </select>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Latitude Wilayah</label>
                                <input type="text" class="form-control" id="latitude" name="latitude">
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Longitude Wilayah</label>
                                <input type="text" class="form-control" id="longitude" name="longitude">
                            </div>
                        </div>
                     </div>
                    

                     <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Postal Code</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code">
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Telphone Wilayah</label>
                                <input type="text" class="form-control" id="telphone" name="telphone">
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Email Wilayah</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                     </div>
                    <div class="row">
                        <div class="col-md">
                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea name="address" rows="3" cols="80" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="button" class="btn btn-primary">Input</button>
                </form>
            </div>
            <!-- <div class="modal-footer justify-content-between"> -->
            <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            <!-- </div> -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
