@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show mt-4 mr-4" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <ul class="p-0 m-0" style="list-style: none;">
       <li>{{ $message }}</li>
    </ul>
</div>
@endif

<div class="equipments-wrapper mt-4">
    <div class="row col-md-12 col-sm-12 mb-4">
        <h3 class="text-uppercase font-weight-normal mt-2 mr-4">Equipment Management</h3>
    </div>
    <div class="row col-lg-12 col-md-12 col-sm-12">
        <div class="d-flex justify-content-start col-lg-12 col-md-12 col-sm-12 p-0 mb-4">
            <button type="button" class="btn btn-primary btn-add-equipment" title="New Equipment">
                <i class="bi bi-pencil-fill align-middle"></i> Create New Equipment
            </button>
        </div>
        <div class="table-responsive table-wrapper equipments-table-wrapper mb-4">
            <table class="table table-hover datatable w-100 b-s-b-b cell-border" id="equipments-table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" class="pl-2 pr-2"><div class="equipments-table-header">Code</div></th>
                        <th scope="col" class="pl-2 pr-2"><div class="equipments-table-header">Name</div></th>
                        <th scope="col" class="pl-2 pr-2"><div class="equipments-table-header">Image</div></th>
                        <th scope="col" class="pl-2 pr-2" width="100"><div class="equipments-table-header">Status</div></th>
                        <th scope="col" class="pl-2 pr-2"><div class="equipments-table-header">Total Quantity</div></th>
                        <th scope="col" class="pl-2 pr-2"><div class="equipments-table-header">Quantity In Storage</div></th>
                        <th scope="col" class="pl-2 pr-2"><div class="equipments-table-header">Category</div></th>
                        <th scope="col" class="no-sort text-center" width="180">
                            <div class="equipments-table-header">Actions</div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($equipments as $equipment)
                    <tr data-id="{{ $equipment->id }}">
                        <td class="text-white pl-2 pr-2">{{ $equipment->equ_code }}</td>
                        <td class="text-white pl-2 pr-2">{{ $equipment->equ_name }}</td>
                        <td class="text-center text-white pl-2 pr-2">
                            @if (!empty($equipment->equ_image))
                            <img src="{{ asset('/images/equipments/' . $equipment->equ_image) }}"
                                width="100"
                                alt="Equipment Image" />
                            @endif
                        </td>
                        <td class="text-white pl-2 pr-2 {{ getStatusClassName('equipment', $equipment->equ_status) }}">{{ getStatusName('equipment', $equipment->equ_status) }}</td>
                        <td class="text-white pl-2 pr-2 text-right {{ getQntClassName($equipment->equ_total_qnt) }}">{{ $equipment->equ_total_qnt }}</td>
                        <td class="text-white pl-2 pr-2 text-right {{ getQntClassName($equipment->equ_current_qnt) }}">{{ $equipment->equ_current_qnt }}</td>
                        <td class="text-white pl-2 pr-2">{{ $equipment->category->cat_name }}</td>
                        <td class="text-white text-left">
                            <button type="button" class="btn btn-sm btn-dark btn-view-equipment" title="View Detail">
                                <i class="bi bi-eye-fill align-middle"></i> View
                            </button>
                            <button type="button" class="btn btn-sm btn-primary btn-edit-equipment" title="Edit">
                                <i class="bi bi-pencil-square align-middle"></i> Edit
                            </button>
                            @if($equipment->equ_total_qnt == $equipment->equ_current_qnt)
                            <button type="button" class="btn btn-sm btn-danger btn-delete-equipment" title="Delete">
                                <i class="bi bi-x-circle-fill align-middle"></i> Delete
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Equipment Modal -->
<div class="modal fade" id="delete-equipment-modal" tabindex="-1" role="dialog" aria-labelledby="delete-equipment-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 550px">
        <div class="modal-content n-b-r text-dark">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bolder" id="delete-equipment-modal-header-title">Delete Equipment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mt-4 mb-4 pl-4 pr-4">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="">Are you sure you want to delete this equipment?</label>
                        </div>
                        <div class="form-group mb-0">
                            <label class="mb-0 equ-info"></label>
                            <input type="hidden" name="equ_id" id="equ_id" value="" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal-close btn-w-bigger" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-w-bigger text-uppercase" id="btn-delete-equipment-confirm">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- View Equipment Modal -->
<div class="modal fade" id="view-equipment-modal" tabindex="-1" role="dialog" aria-labelledby="view-equipment-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content n-b-r text-dark">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bolder" id="view-equipment-modal-header-title">Equipment Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mt-2 mb-2 pl-4 pr-4">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="equ_code" class="font-weight-bolder">Code</label>
                            <input class="form-control n-b-r"
                                    type="text"
                                    id="equ_code"
                                    name="equ_code"
                                    value=""
                                    readonly
                            />
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="equ_name" class="font-weight-bolder">Name</label>
                            <input class="form-control n-b-r"
                                    type="text"
                                    id="equ_name"
                                    name="equ_name"
                                    value=""
                                    readonly
                            />
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="equ_desc" class="font-weight-bolder">Desc/Spec</label>
                            <textarea class="form-control n-b-r" name="equ_desc" id="equ_desc" rows="5" readonly></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group text-center equ_image_wrapper">
                            <img src="" class="img-fluid" alt="Equipment Image" id="equ_image" />
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="equ_total_qnt" class="font-weight-bolder">Total Quantity</label>
                            <input class="form-control n-b-r"
                                    type="text"
                                    id="equ_total_qnt"
                                    name="equ_total_qnt"
                                    value=""
                                    readonly
                            />
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="equ_current_qnt" class="font-weight-bolder">Quantity in Storage</label>
                            <input class="form-control n-b-r"
                                    type="text"
                                    id="equ_current_qnt"
                                    name="equ_current_qnt"
                                    value=""
                                    readonly
                            />
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="equ_status" class="font-weight-bolder">Status</label>
                            <input class="form-control n-b-r"
                                    type="text"
                                    id="equ_status"
                                    name="equ_status"
                                    value=""
                                    readonly
                            />
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="cat_name" class="font-weight-bolder">Category</label>
                            <input class="form-control n-b-r"
                                    type="text"
                                    id="cat_name"
                                    name="cat_name"
                                    value=""
                                    readonly
                            />
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="created_at" class="font-weight-bolder">Created At</label>
                            <input class="form-control n-b-r"
                                    type="text"
                                    id="created_at"
                                    name="created_at"
                                    value=""
                                    readonly
                            />
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="updated_at" class="font-weight-bolder">Updated At</label>
                            <input class="form-control n-b-r"
                                    type="text"
                                    id="updated_at"
                                    name="updated_at"
                                    value=""
                                    readonly
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal-close btn-w-normal" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- New Equipment Modal -->
<div class="modal fade" id="new-equipment-modal" tabindex="-1" role="dialog" aria-labelledby="new-equipment-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content n-b-r text-dark">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bolder" id="new-equipment-modal-header-title">New Equipment Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="new-equipment-form" method="post">
                    <div class="row mt-2 mb-2 pl-4 pr-4">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="equ_code" class="font-weight-bolder">Code</label>
                                <input class="form-control n-b-r"
                                        type="text"
                                        id="equ_code"
                                        name="equ_code"
                                        value=""
                                />
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="equ_name" class="font-weight-bolder">Name</label>
                                <input class="form-control n-b-r"
                                        type="text"
                                        id="equ_name"
                                        name="equ_name"
                                        value=""
                                />
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="equ_desc" class="font-weight-bolder">Desc/Spec</label>
                                <textarea class="form-control n-b-r" name="equ_desc" id="equ_desc" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group equ_image_wrapper">
                                <label for="equ_image" class="font-weight-bolder">Image</label>
                                <div class="col-lg-12 col-md-12 col-sm-12 p-0 text-center">
                                    <div id="msg"></div>
                                    <input type="file" id="equ_image" name="equ_image" class="file" accept="image/*">
                                    <div class="input-group my-3">
                                        <input type="text" class="form-control n-b-r" disabled placeholder="Upload File" id="file">
                                        <div class="input-group-append">
                                            <button type="button" class="browse btn btn-primary n-b-r">Browse...</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 p-0 text-center">
                                    <img src="{{ asset('/images/equipments/empty-image.png') }}" id="preview" class="img-thumbnail">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="equ_total_qnt" class="font-weight-bolder">Total Quantity</label>
                                <input class="form-control n-b-r"
                                        type="number"
                                        id="equ_total_qnt"
                                        name="equ_total_qnt"
                                        value="100"
                                        min="0"
                                />
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="cat_name" class="font-weight-bolder">Category</label>
                                <select name="cat_id" id="cat_id" class="form-control n-b-r">
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal-close btn-w-normal" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success btn-w-normal text-uppercase" id="btn-save-new-equipment">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Equipment Modal -->
<div class="modal fade" id="edit-equipment-modal" tabindex="-1" role="dialog" aria-labelledby="edit-equipment-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content n-b-r text-dark">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bolder" id="edit-equipment-modal-header-title">Equipment Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-equipment-form" method="post">
                    <input type="hidden" name="equ_id" id="equ_id" />
                    <input type="hidden" name="equ_old_code" id="equ_old_code" />
                    <div class="row mt-2 mb-2 pl-4 pr-4">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="equ_code" class="font-weight-bolder">Code</label>
                                <input class="form-control n-b-r"
                                        type="text"
                                        id="equ_code"
                                        name="equ_code"
                                        value=""
                                />
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="equ_name" class="font-weight-bolder">Name</label>
                                <input class="form-control n-b-r"
                                        type="text"
                                        id="equ_name"
                                        name="equ_name"
                                        value=""
                                />
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="equ_desc" class="font-weight-bolder">Desc/Spec</label>
                                <textarea class="form-control n-b-r" name="equ_desc" id="equ_desc" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group equ_image_wrapper">
                                <label for="equ_image" class="font-weight-bolder">Image</label>
                                <div class="col-lg-12 col-md-12 col-sm-12 p-0 text-center">
                                    <div id="msg"></div>
                                    <input type="file" id="equ_image" name="equ_image" class="file" accept="image/*">
                                    <div class="input-group my-3">
                                        <input type="text" class="form-control n-b-r" disabled placeholder="Upload File" id="edit_equ_image_file">
                                        <div class="input-group-append">
                                            <button type="button" class="browse btn btn-primary n-b-r">Browse...</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 p-0 text-center">
                                    <img src="{{ asset('/images/equipments/empty-image.png') }}" id="edit_equ_image_preview" class="img-thumbnail">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="equ_total_qnt" class="font-weight-bolder">Total Quantity</label>
                                <input class="form-control n-b-r"
                                        type="number"
                                        id="equ_total_qnt"
                                        name="equ_total_qnt"
                                        value="100"
                                        min="0"
                                />
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="equ_status" class="font-weight-bolder">Status</label>
                                <input class="form-control n-b-r"
                                        type="text"
                                        id="equ_status"
                                        name="equ_status"
                                        value=""
                                        readonly
                                />
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="cat_name" class="font-weight-bolder">Category</label>
                                <select name="cat_id" id="cat_id" class="form-control n-b-r">
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal-close btn-w-normal" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success btn-w-normal text-uppercase" id="btn-update-equipment">Save</button>
            </div>
        </div>
    </div>
</div>
