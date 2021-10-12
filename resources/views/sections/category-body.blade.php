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
    <div class="col-lg-12 col-md-12 col-sm-12">
        <h3 class="text-uppercase font-weight-normal mt-4 mb-4">Category Management</h3>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6 tree-view">
                <ul class="category">
                @foreach ($tree as $category)
                    <li class="sub-category"
                        data-id="{{ $category['id'] }}"
                        data-name="{{ $category['name'] }}"
                        data-p-cat="{{ $category['parentID'] }}">
                        {{ $category['name'] }} ({{ $category['count'] }})
                    </li>

                    @if (isset($category['children']) && count($category['children']))
                        @include('sections.sub-category-body', ['subcategories' => $category['children']])
                    @endif

                @endforeach
                </ul>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <form method="POST" action="{{ route('category.store') }}">
                    @csrf
                    <input type="hidden" id="cat_id" name="cat_id" />
                    <div class="form-group">
                        <label for="cat_name">Category Name</label>
                        <input class="form-control n-b-r"
                                type="text"
                                id="cat_name"
                                name="cat_name"
                                value=""
                                placeholder=""
                                required
                        />
                    </div>
                    <div class="form-group">
                        <label for="p_cat">Parent Category</label>
                        <select name="p_cat" id="p_cat" class="form-control n-b-r">
                            @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->cat_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-dark" id="btn-save">Save</button>
                        <button type="button" class="btn btn-primary" id="btn-new">New</button>
                        <button type="button" class="btn btn-danger" id="btn-delete">Delete</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <p class="font-weight-bolder">NOTE:</p>
                <p class="text-danger font-weight-bold">Can't delete root category(All).</p>
                <p class="text-danger font-weight-bold">Can't delete the category with equipment.</p>
                <p class="text-danger font-weight-bold">Can't delete the category with sub category.</p>
            </div>
        </div>
    </div>
</div>