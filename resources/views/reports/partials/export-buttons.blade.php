@php
    $baseQuery = request()->except('export');
@endphp
<div class="btn-group mb-3" role="group">
    <a href="{{ request()->url() . '?' . http_build_query(array_merge($baseQuery, ['export' => 'excel'])) }}" class="btn btn-success btn-sm">
        <i class="ti-download"></i> Export Excel
    </a>
    <a href="{{ request()->url() . '?' . http_build_query(array_merge($baseQuery, ['export' => 'pdf'])) }}" class="btn btn-danger btn-sm">
        <i class="ti-file"></i> Export PDF
    </a>
</div>
