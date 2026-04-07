@extends('layouts.app')

@section('content')
<div class="container-fluid p-0" style="background-color: #f4f7f6; min-height: 100vh;">
    <div class="p-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark m-0">Administrator Portal</h3>
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; font-weight: bold;">
                {{ substr(Auth::user()->first_name ?? 'A', 0, 1) }}
            </div>
        </div>

        {{-- Stat Cards (3-Column Layout) --}}
        <div class="row g-4 mb-5">
            {{-- Active Health Workers --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-white h-100 p-3" style="background: linear-gradient(135deg, #2563eb, #3b82f6); border-radius: 15px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="bg-white bg-opacity-25 rounded-3 p-2">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                        <span class="badge bg-white bg-opacity-25">+2</span>
                    </div>
                    <div class="mt-4">
                        <p class="mb-1 small opacity-75 fw-bold">Active Health Workers</p>
                        <h2 class="fw-bold mb-1">{{ $activeHealthWorkersCount }}</h2>
                        <small class="opacity-75">Currently active</small>
                    </div>
                </div>
            </div>

            {{-- Staff Activities --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-white h-100 p-3" style="background: linear-gradient(135deg, #10b981, #34d399); border-radius: 15px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="bg-white bg-opacity-25 rounded-3 p-2">
                            <i class="fas fa-chart-line fa-lg"></i>
                        </div>
                        <span class="badge bg-white bg-opacity-25">+18%</span>
                    </div>
                    <div class="mt-4">
                        <p class="mb-1 small opacity-75 fw-bold">Staff Activities</p>
                        <h2 class="fw-bold mb-1">{{ $staffActivityCount }}</h2>
                        <small class="opacity-75">Recorded actions</small>
                    </div>
                </div>
            </div>

            {{-- Reports Generated --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-white h-100 p-3" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa); border-radius: 15px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="bg-white bg-opacity-25 rounded-3 p-2">
                            <i class="fas fa-file-alt fa-lg"></i>
                        </div>
                        <span class="badge bg-white bg-opacity-25">+12%</span>
                    </div>
                    <div class="mt-4">
                        <p class="mb-1 small opacity-75 fw-bold">Reports Generated</p>
                        <h2 class="fw-bold mb-1">{{ $reportsGeneratedCount }}</h2>
                        <small class="opacity-75">This quarter</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Staff Activities Section --}}
        <div class="card border-0 shadow-sm p-4" style="border-radius: 20px;">
            <h5 class="fw-bold text-dark mb-4">Recent Staff Activities</h5>
            
            <div class="list-group list-group-flush">
                @forelse($recentActivities as $activity)
                    <div class="list-group-item border-0 px-0 py-3 d-flex align-items-center justify-content-between bg-transparent">
                        <div class="d-flex align-items-center">
                            {{-- User Avatar Initials --}}
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 45px; height: 45px; font-weight: bold;">
                                {{ substr($activity->user->first_name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">{{ $activity->user->first_name ?? 'Unknown' }} {{ $activity->user->last_name ?? 'User' }}</h6>
                                <p class="mb-0 small text-muted">{{ $activity->action_details }}</p>
                            </div>
                        </div>
                        <div class="text-end text-muted small">
                            {{ $activity->created_at->diffForHumans() }}
                        </div>
                    </div>
                    {{-- Spacer between items --}}
                    @if(!$loop->last)
                        <div style="height: 10px;"></div>
                    @endif
                @empty
                    <p class="text-center text-muted py-4">No recent activities found.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling to match your HEART image exactly */
    .card { transition: transform 0.2s ease-in-out; }
    .card:hover { transform: translateY(-5px); }
    .list-group-item { border-radius: 12px !important; transition: background 0.2s; }
    .list-group-item:hover { background-color: #f8fafc !important; }
</style>
@endsection