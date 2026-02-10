@extends('layouts.app')

@section('title', config('app.name') . ' - Financial Year Summary')
@section('page_title', 'Financial Year Summary')
@section('breadcrumb', 'Reports / Financial Year Summary')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    @include('reports.partials.export-buttons')
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Financial Year</th>
                                <th>Start</th>
                                <th>End</th>
                                <th class="text-end">Total Allocated (Rs.)</th>
                                <th class="text-end">Beneficiaries</th>
                                <th class="text-end">Disbursed (Rs.)</th>
                                <th class="text-end">Paid (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($years as $fy)
                                <tr>
                                    <td>{{ $fy->name }}</td>
                                    <td>{{ $fy->start_date?->format('d M Y') }}</td>
                                    <td>{{ $fy->end_date?->format('d M Y') }}</td>
                                    <td class="text-end">{{ number_format($fy->total_allocated ?? 0, 2) }}</td>
                                    <td class="text-end">{{ number_format($fy->beneficiaries_count ?? 0) }}</td>
                                    <td class="text-end">{{ number_format($fy->amount_disbursed ?? 0, 2) }}</td>
                                    <td class="text-end">{{ number_format($fy->amount_paid ?? 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
