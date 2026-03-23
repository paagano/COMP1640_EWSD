<x-app-layout>
<div class="container py-4">

    <h2 class="fw-bold mb-4">My Profile Settings</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- =============================== --}}
    {{-- Profile Information --}}
    {{-- =============================== --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light fw-semibold">
            Profile Information
        </div>
        <div class="card-body">

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">

            {{-- Profile Picture --}}
            <div class="col-md-3 text-center mb-3">
                <div class="mb-2">

                    @if($user->profile_photo)
                        <!-- <img src="{{ asset('storage/'.$user->profile_photo) }}" -->
                            <img src="{{ $user->profile_photo }}"
                            class="rounded-circle"
                            width="120"
                            height="120"
                            style="object-fit: cover;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}"
                            class="rounded-circle"
                            width="120"
                            height="120"
                            style="object-fit: cover;">
                    @endif

                </div>

                <input type="file"
                    name="profile_photo"
                    class="form-control form-control-sm">

                {{-- validation error --}}
                @error('profile_photo')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Basic Info --}}
            <div class="col-md-9">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="form-control">
                    
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="form-control">

                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Update Profile
                </button>

            </div>
        </div>
    </form>

        </div>
    </div>


    {{-- =============================== --}}
    {{-- Change Password (AUTO-OPEN ON ERROR) --}}
    {{-- =============================== --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center"
             style="cursor:pointer;"
             data-bs-toggle="collapse"
             data-bs-target="#changePasswordCollapse">

            <span>Change Password</span>

            <span class="text-muted">
                ⬇
            </span>
        </div>

        <div id="changePasswordCollapse"
             class="collapse {{ $errors->has('current_password') || $errors->has('password') ? 'show' : '' }}">

            <div class="card-body">

                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Current Password</label>
                        <input type="password"
                               name="current_password"
                               class="form-control @error('current_password') is-invalid @enderror">

                        @error('current_password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">New Password</label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror">

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Confirm Password</label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control">
                    </div>

                    <button type="submit" class="btn btn-success">
                        Update Password
                    </button>

                </form>

            </div>
        </div>

    </div>


    {{-- =============================== --}}
    {{-- Login Audit History (ROLE-BASED DEFAULT OPEN) --}}
    {{-- =============================== --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center"
             style="cursor:pointer;"
             data-bs-toggle="collapse"
             data-bs-target="#loginHistoryCollapse">

            <span>Login History (Last 5 Sessions)</span>

            <span class="text-muted">
                ⬇
            </span>
        </div>

        {{-- ADMIN = OPEN | OTHERS = CLOSED --}}
        <div id="loginHistoryCollapse"
             class="collapse {{ auth()->user()->hasRole('Admin') ? 'show' : '' }}">

            <div class="card-body">

                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Date & Time</th>
                            <th>IP Address</th>
                            <th>Browser / Platform</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loginHistory ?? [] as $log)
                            <tr>

                                <td>
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i A') }}
                                </td>

                                <td>{{ $log->ip_address }}</td>

                                <td class="small">
                                    {{ $log->browser ?? 'N/A' }} 
                                    ({{ $log->platform ?? 'N/A' }})
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    No login history available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

        </div>

    </div>


    {{-- =============================== --}}
    {{-- Deactivate Account --}}
    {{-- =============================== --}}
    @unless(auth()->user()->hasRole('Admin'))
    <div class="card shadow-sm border-danger mb-4">
        <div class="card-header bg-danger text-white fw-semibold">
            Danger Zone
        </div>
        <div class="card-body">

            <p class="text-danger">
                Deactivating your account will disable your access to the system.
                You can contact Admin to reactivate it.
            </p>

            <form method="POST" action="{{ route('profile.deactivate') }}">
                @csrf
                @method('DELETE')

                <button type="submit"
                        class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to deactivate your account?')">
                    Deactivate Account
                </button>
            </form>

        </div>
    </div>
    @endunless

</div>
</x-app-layout>
