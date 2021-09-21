@foreach ($subcategories as $subcategory)
 <ul class="category">
    <li class="sub-category"
        data-id="{{ $subcategory['id'] }}"
        data-name="{{ $subcategory['name'] }}"
        data-p-cat="{{ $subcategory['parentID'] }}">
        {{ $subcategory['name'] }} ({{ $subcategory['count'] }})
    </li> 
    @if (isset($subcategory['children']) && count($subcategory['children']))
        @include('sections.sub-category-body',['subcategories' => $subcategory['children']])
    @endif
 </ul> 
@endforeach