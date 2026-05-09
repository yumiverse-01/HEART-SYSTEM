@extends('layouts.app')

@section('content')
<div class="container-fluid p-0" style="background-color: #f4f7f6; min-height: 100vh;">
    <div class="p-3 p-md-4">
        
        {{-- Header: Wraps on very small screens --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <h3 class="fw-bold text-dark m-0" style="font-size: 1.5rem;">Admin Portal</h3>
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                 style="width: 40px; height: 40px; font-weight: bold; flex-shrink: 0;">
                {{ substr(Auth::user()->first_name ?? 'A', 0, 1) }}
            </div>
        </div>

        {{-- Stat Cards: Stacks on mobile (col-12) --}}
        <div class="row g-3 g-md-4 mb-4 mb-md-5">
            {{-- Active Workers --}}
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm text-white h-100 p-3" 
                     style="background: linear-gradient(135deg, #2563eb, #3b82f6); border-radius: 15px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="bg-white bg-opacity-25 rounded-3 p-2">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                        <span class="badge bg-white bg-opacity-25">+2</span>
                    </div>
                    <div class="mt-3">
                        <p class="mb-0 small opacity-75 fw-bold">Active Workers</p>
                        <h2 class="fw-bold mb-0">{{ $activeHealthWorkersCount }}</h2>
                        <small class="opacity-75">Currently active</small>
                    </div>
                </div>
            </div>

            {{-- Staff Activities --}}
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm text-white h-100 p-3" 
                     style="background: linear-gradient(135deg, #10b981, #34d399); border-radius: 15px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="bg-white bg-opacity-25 rounded-3 p-2">
                            <i class="fas fa-chart-line fa-lg"></i>
                        </div>
                        <span class="badge bg-white bg-opacity-25">+18%</span>
                    </div>
                    <div class="mt-3">
                        <p class="mb-0 small opacity-75 fw-bold">Staff Activities</p>
                        <h2 class="fw-bold mb-0">{{ $staffActivityCount }}</h2>
                        <small class="opacity-75">Recorded actions</small>
                    </div>
                </div>
            </div>

            {{-- Reports Generated --}}
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm text-white h-100 p-3" 
                     style="background: linear-gradient(135deg, #8b5cf6, #a78bfa); border-radius: 15px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="bg-white bg-opacity-25 rounded-3 p-2">
                            <i class="fas fa-file-alt fa-lg"></i>
                        </div>
                        <span class="badge bg-white bg-opacity-25">+12%</span>
                    </div>
                    <div class="mt-3">
                        <p class="mb-0 small opacity-75 fw-bold">Reports</p>
                        <h2 class="fw-bold mb-0">{{ $reportsGeneratedCount }}</h2>
                        <small class="opacity-75">This quarter</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Activities --}}
        <!-- <div class="card border-0 shadow-sm p-3 p-md-4" style="border-radius: 20px;">
            <h5 class="fw-bold text-dark mb-3">Recent Activities</h5>
            
            <div class="list-group list-group-flush">
                @forelse($recentActivities as $activity)
                    <div class="list-group-item border-0 px-0 py-3 bg-transparent">
                        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" 
                                     style="width: 40px; height: 40px; font-weight: bold; flex-shrink: 0;">
                                    {{ substr($activity->user->first_name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">
                                        {{ $activity->user->first_name ?? 'Unknown' }}
                                    </h6>
                                    <p class="mb-0 small text-muted text-break">
                                        {{ $activity->action_details }}
                                    </p>
                                </div>
                            </div>
                            <div class="ms-auto text-end text-muted" style="font-size: 0.75rem;">
                                {{ $activity->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @if(!$loop->last)
                        <hr class="my-0 opacity-10">
                    @endif
                @empty
                    <p class="text-center text-muted py-4">No activities found.</p>
                @endforelse
            </div>
        </div> -->
    </div>
</div>

<style>
    /* Better touch targets for mobile */
    .card { transition: transform 0.2s ease-in-out; }
    .card:active { transform: scale(0.98); }
    @media (min-width: 768px) {
        .card:hover { transform: translateY(-5px); }
    }
    .list-group-item { border-radius: 12px !important; }
</style>
@endsection