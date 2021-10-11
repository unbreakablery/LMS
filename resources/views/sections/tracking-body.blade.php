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

<div class="bookings-wrapper mt-4">
    <div class="row col-md-12 col-sm-12 mb-4">
        <h3 class="text-uppercase font-weight-normal mt-2 mr-4">Tracking Equipment</h3>
    </div>
    <div class="row col-lg-12 col-md-12 col-sm-12">
        <div class="table-responsive table-wrapper bookings-table-wrapper mb-4">
            <table class="table table-hover datatable w-100 b-s-b-b cell-border" id="bookings-table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" class="pl-2 pr-2 text-center" width="200"><div class="bookings-table-header">Tracking Time</div></th>
                        <th scope="col" class="pl-2 pr-2"><div class="bookings-table-header">Equipment</div></th>
                        <th scope="col" class="pl-2 pr-2"><div class="bookings-table-header">Category</div></th>
                        <th scope="col" class="pl-2 pr-2"><div class="bookings-table-header">Booking ID</div></th>
                        <th scope="col" class="pl-2 pr-2"><div class="bookings-table-header">Staff</div></th>
                        <th scope="col" class="pl-2 pr-2"><div class="bookings-table-header">Status</div></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trackings as $tracking)
                    <tr data-id="{{ $tracking->id }}">
                        <td class="text-white pl-2 pr-2 text-center">{{ $tracking->tracking_time }}</td>
                        <td class="text-white pl-2 pr-2">
                            <a href="javascript:void(0);" class="equipment-link" data-id="{{ $tracking->equ_id }}">
                                {{ $tracking->equ_name }}
                            </a>
                        </td>
                        <td class="text-white pl-2 pr-2">
                            {{ $tracking->equ_cat }}
                        </td>
                        <td class="text-white pl-2 pr-2">
                            <a href="javascript:void(0);" class="booking-link" data-id="{{ $tracking->booking_id }}">
                                {{ 'B-' . $tracking->booking_id }}
                            </a>
                        </td>
                        <td class="text-white pl-2 pr-2">
                            {{ $tracking->staff }}
                        </td>
                        <td class="text-white pl-2 pr-2 {{ $tracking->status_class_name }}">
                            {{ $tracking->status_name }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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

<!-- Edit Booking Modal -->
<div class="modal fade" id="edit-booking-modal" tabindex="-1" role="dialog" aria-labelledby="edit-booking-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content n-b-r text-dark">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bolder" id="edit-booking-modal-header-title">Booking Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-booking-form" method="post">
                    <input type="hidden" name="booking_id" id="booking_id" />
                    <div class="row mt-2 mb-2 pl-4 pr-4">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="equ_name" class="font-weight-bolder">Equipment</label>
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
                                <label for="booking_date" class="font-weight-bolder">Booking Date</label>
                                <input class="form-control n-b-r"
                                        type="text"
                                        id="booking_date"
                                        name="booking_date"
                                        value=""
                                        readonly
                                />
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="booking_start" class="font-weight-bolder">Booking Start</label>
                                <input class="form-control n-b-r date"
                                        type="text"
                                        id="booking_start"
                                        name="booking_start"
                                        value=""
                                />
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="booking_end" class="font-weight-bolder">Booking End</label>
                                <input class="form-control n-b-r date"
                                        type="text"
                                        id="booking_end"
                                        name="booking_end"
                                        value=""
                                />
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="status" class="font-weight-bolder">Status</label>
                                <input class="form-control n-b-r"
                                        type="text"
                                        id="status"
                                        name="status"
                                        value=""
                                        readonly
                                />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal-close btn-w-normal" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success btn-w-normal text-uppercase" id="btn-change-booking-period">Save</button>
            </div>
        </div>
    </div>
</div>
