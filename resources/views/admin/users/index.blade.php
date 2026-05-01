@extends('admin_layout')

@section('content')

<style>
    /* Avatar Circle */
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        margin-right: 15px;
    }

    /* Random colors for avatars based on ID logic or static classes */
    .bg-soft-primary {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    .bg-soft-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .bg-soft-success {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
    }

    .bg-soft-secondary {
        background-color: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }

    /* Role Badge */
    .role-badge {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Table Styling */
    .table thead th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #6c757d;
        font-weight: 700;
        border-bottom-width: 1px;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .table tbody td {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
</style>

<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">{{ __('site.User Management') }}</h4>
        <p class="text-muted small mb-0">{{ __('site.Manage access and view registered users.') }}</p>
    </div>
    <div>
        <span class="badge bg-white text-dark shadow-sm border px-3 py-2">
            {{ __('site.Total Users') }}: <strong>{{ $users->total() }}</strong>
        </span>
    </div>
</div>

<!-- Users Table Card -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">{{ __('site.User Profile') }}</th>
                        <th>{{ __('site.Phone') }}</th>
                        <th>{{ __('site.Role') }}</th>
                        <th>{{ __('site.Joined Date') }}</th>
                        <th class="text-end pe-4">{{ __('site.Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <!-- User Profile (Avatar + Name + Email) -->
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <!-- Initials Avatar -->
                                <div class="user-avatar {{ $user->role == 'admin' ? 'bg-soft-danger' : 'bg-soft-primary' }}">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">{{ $user->name }}</h6>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>

                        <!-- Phone -->
                        <td>
                            @if($user->phone)
                            <span class="text-dark fw-medium">{{ $user->phone }}</span>
                            @else
                            <span class="text-muted small">N/A</span>
                            @endif
                        </td>

                        <!-- Role Badge -->
                        <td>
                            @if($user->role === 'admin')
                            <span class="role-badge bg-soft-danger">
                                <i class="bi bi-shield-lock-fill me-1"></i> {{ __('site.Admin') }} 
                            </span>
                            @else
                            <span class="role-badge bg-soft-secondary">
                                <i class="bi bi-person-fill me-1"></i> {{ __('site.User') }} 
                            </span>
                            @endif
                        </td>

                        <!-- Joined Date -->
                        <td>
                            <span class="text-muted small">
                                <i class="bi bi-calendar3 me-1"></i> {{ $user->created_at->format('d M, Y') }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="text-end pe-4">
                            @if($user->id !== Auth::id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to permanently delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger" title="Delete User">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @else
                            <span class="badge bg-light text-muted border">{{ __('site.Current User') }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Footer -->
    <div class="card-footer bg-white border-0 py-3 d-flex justify-content-end">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection