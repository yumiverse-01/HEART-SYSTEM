@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('reports.index') }}" class="text-decoration-none small text-muted"><i class="fas fa-arrow-left"></i> Back</a>
        <h3 class="mt-2"><i class="fas fa-user-check text-primary"></i> Event Attendance Report</h3>
    </div>
    <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
        <i class="fas fa-search"></i> Search / Filter
    </button>
</div>

<div class="collapse mb-4 show" id="filterCollapse">
    <div class="card card-body border-0 shadow-sm bg-light">
        <form method="GET" action="{{ route('reports.attendance') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Specific Event</label>
                <select name="event_id" class="form-select">
                    <option value="">All Active Events</option>
                    @foreach($events as $e)
                        <option value="{{ $e->event_id }}" {{ request('event_id') == $e->event_id ? 'selected' : '' }}>
                            {{ $e->event_name }} ({{ \Carbon\Carbon::parse($e->event_date)->format('M d') }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Generate</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-nowrap">
            <thead class="bg-light text-muted small">
                <tr>
                    <th class="ps-4 py-3">BENEFICIARY</th>
                    <th>EVENT ATTENDED</th>
                    <th>DATE</th>
                    <th class="text-end pe-4">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $a)
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold">{{ $a->beneficiary->first_name }} {{ $a->beneficiary->last_name }}</div>
                    </td>
                    <td>{{ $a->event->event_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($a->event->event_date)->format('M d, Y') }}</td>
                    <td class="text-end pe-4"><span class="badge bg-success">Present</span></td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-5">No attendance data found for selection.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection