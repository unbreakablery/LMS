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
        <h3 class="text-uppercase font-weight-normal mt-2 mr-4">Equipments</h3>
    </div>
    <div class="row col-lg-12 col-md-12 col-sm-12">
        <div class="table-responsive table-wrapper equipments-table-wrapper mb-4">
            <table class="table table-hover datatable w-100 b-s-b-b cell-border" id="equipments-table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" class="pl-2 pr-2"><div class="equipments-table-header">Code</div></th>
                        <th scope="col" class="pl-2 pr-2"><div class="equipments-table-header">Name</div></th>
                        <th scope="col" class="pl-2 pr-2"><div class="equipments-table-header">Image</div></th>
                        <th scope="col" class="pl-2 pr-2"><div class="equipments-table-header">Status</div></th>
                        <th scope="col" class="pl-2 pr-2"><div class="equipments-table-header">Total Quantity</div></th>
                        <th scope="col" class="pl-2 pr-2"><div class="equipments-table-header">Quantity In Storage</div></th>
                        <th scope="col" class="pl-2 pr-2"><div class="equipments-table-header">Category</div></th>
                        <th scope="col" class="no-sort text-center" width="210">
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
                        <td class="text-white pl-2 pr-2 {{ getQntClassName($equipment->equ_total_qnt) }}">{{ $equipment->equ_total_qnt }}</td>
                        <td class="text-white pl-2 pr-2 {{ getQntClassName($equipment->equ_current_qnt) }}">{{ $equipment->equ_current_qnt }}</td>
                        <td class="text-white pl-2 pr-2">{{ $equipment->category->cat_name }}</td>
                        <td class="text-left text-white">
                            <button type="button" class="btn btn-sm btn-dark btn-view-equipment" title="View Details">
                                <i class="bi bi-eye-fill align-middle"></i> View Details
                            </button>
                            @if($equipment->equ_status)
                            <button type="button" class="btn btn-sm btn-primary btn-request-booking" title="Request Booking">
                                <i class="bi bi-basket2-fill align-middle"></i> Request Booking
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

<!-- Request Booking Modal -->
<div class="modal fade" id="request-booking-modal" tabindex="-1" role="dialog" aria-labelledby="request-booking-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 550px">
        <div class="modal-content n-b-r text-dark">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bolder" id="request-booking-modal-header-title">Request Booking</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mt-4 mb-4 pl-4 pr-4">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group mb-0">
                            <input type="hidden" name="equ_id" id="equ_id" value="" />
                        </div>
                        <div class="form-group mb-4">
                            <label class="mb-0 equ-info" for="booking_qnt">Booking Quantity</label>
                            <input type="number" class="form-control n-b-r" name="booking_qnt" id="booking_qnt" value="1" min="1"/>
                            <span class="text-success font-weight-bold" id="equ_current_qnt"></span>
                        </div>
                        <div class="form-group mb-4">
                            <label class="mb-0 equ-info" for="booking_start">Booking Start</label>
                            <input type="text" class="form-control n-b-r date" name="booking_start" id="booking_start" />
                        </div>
                        <div class="form-group mb-0">
                            <label class="mb-0 equ-info" for="booking_end">Booking End</label>
                            <input type="text" class="form-control n-b-r date" name="booking_end" id="booking_end" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal-close btn-w-bigger" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success btn-w-bigger text-uppercase" id="btn-request-booking-confirm">Yes</button>
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