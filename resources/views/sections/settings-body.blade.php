@if(count($errors) > 0 )
<div class="alert alert-danger alert-dismissible fade show mt-4 mr-4" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <ul class="p-0 m-0" style="list-style: none;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row ml-0 mr-0 justify-content-center">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h3 class="text-uppercase font-weight-normal mt-4 mb-4">Settings</h3>
        <form action="{{ route('settings.store.user') }}" method='post' autocomplete="off">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}" />
            <h3 class="setting-sub-title">Account Information</h3>
            <div class="row col-lg-12 col-md-12 col-sm-12 mt-2 n-p-lr">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input class="form-control n-b-r @error('first_name') is-invalid @enderror"
                                type="text"
                                id="first_name"
                                name="first_name"
                                value="{{ old('first_name', $user->first_name) }}"
                                placeholder="At least 3 characters..."
                                required
                        />
                        @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input class="form-control n-b-r @error('last_name') is-invalid @enderror"
                                type="text"
                                id="last_name"
                                name="last_name"
                                value="{{ old('last_name', $user->last_name) }}"
                                placeholder="At least 3 characters..."
                                required
                        />
                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control n-b-r @error('email') is-invalid @enderror"
                                type="text"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                placeholder="example: test@test.com"
                                required
                        />
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="col-lg-12 col-md-12 col-sm-12 mt-4">
                    <div class="form-group d-flex justify-content-between">
                        <div class="d-flex justify-content-start">
                            <button type="submit" class="btn btn-grad btn-w-normal" id="btn-save-general-settings">
                                Save Settings
                            </button>
                            <button type="button" class="btn btn-grad btn-w-normal ml-4" id="btn-change-password">
                                Change Password
                            </button>
                        </div>
                        @if (Auth::user()->role != 1)
                        <div class="d-flex justify-content-end align-items-center">
                            <a class="text-right text-danger text-uppercase a-btn-remove-account justify-content-end n-p-lr">
                                Delete Account
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="delete-account-modal" tabindex="-1" role="dialog" aria-labelledby="delete-account-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 600px">
        <div class="modal-content n-b-r text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="delete-account-modal-header-title">Delete Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mt-4 mb-4 pl-4 pr-4">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="">Are you sure you want to delete your account?</label>
                        </div>
                        <div class="form-group mb-0">
                            <label class="mb-0">All your data will be lost and your account will be cancelled.</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-grad btn-w-bigger" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-modal-close btn-w-bigger" id="btn-delete-account">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="change-password-modal" tabindex="-1" role="dialog" aria-labelledby="change-password-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content n-b-r text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-script-modal-header-title">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mt-2 mb-2 pl-4 pr-4">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input class="form-control n-b-r"
                                    type="password"
                                    id="new_password"
                                    name="new_password"
                                    value=""
                                    placeholder="Enter new password..."
                            />
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input class="form-control n-b-r"
                                    type="password"
                                    id="confirm_password"
                                    name="confirm_password"
                                    value=""
                                    placeholder="Enter confirm password..."
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal-close btn-w-normal" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-grad btn-w-normal" id="btn-update-password">Update</button>
            </div>
        </div>
    </div>
</div>