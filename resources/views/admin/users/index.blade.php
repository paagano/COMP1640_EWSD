<x-app-layout>

<style>
/* ============================= */
/* FIX PAGINATION STYLING */
/* ============================= */

.pagination {
    font-size: 14px;
}

.pagination svg {
    width: 14px !important;
    height: 14px !important;
}

.pagination .page-link {
    padding: 6px 10px;
    border-radius: 6px;
}

.pagination .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}

.pagination .page-link:hover {
    background-color: #f1f5f9;
}
</style>


<div class="container py-4">

    <h2 class="mb-3">Manage Users</h2>
    <hr>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


    {{-- ============================= --}}
    {{-- COLLAPSIBLE CREATE USER --}}
    {{-- ============================= --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-light d-flex justify-content-between align-items-center"
             style="cursor:pointer;"
             data-bs-toggle="collapse"
             data-bs-target="#createUserCollapse">

            <h5 class="mb-0">
                Create New User
            </h5>

            <span class="text-muted">
                ⬇
            </span>
        </div>

        <div id="createUserCollapse" class="collapse">
            <div class="card-body">

                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Set User Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Faculty</label>
                        <select name="faculty_id" class="form-select" required>
                            <option value="">Select Faculty</option>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}">
                                    {{ $faculty->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-success me-2">Add User</button>
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-toggle="collapse"
                            data-bs-target="#createUserCollapse">
                        Cancel
                    </button>

                </form>

            </div>
        </div>

    </div>


    {{-- ============================= --}}
    {{-- USER LIST --}}
    {{-- ============================= --}}
    <h4>User List</h4>

    {{-- INTELLIGENT SEARCH --}}
    <form method="GET" id="searchForm" class="mb-3">
        <div class="input-group" style="max-width: 450px;">
            <input type="text"
                   name="search"
                   id="searchInput"
                   class="form-control"
                   placeholder="Search by name, email, role or ID..."
                   value="{{ request('search') }}">
            @if(request('search'))
                <a href="{{ route('admin.users.index') }}"
                   class="btn btn-outline-secondary">Clear</a>
            @endif
        </div>
    </form>

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th style="width:8%">User ID</th>
                <th style="width:20%">Full Name</th>
                <th style="width:20%">Email</th>
                <th style="width:15%">Role</th>
                <th style="width:17%">Faculty</th>
                <th style="width:20%">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->getRoleNames()->first() }}</td>
                    <td>{{ $user->faculty->name ?? '-' }}</td>

                    <td>
                        <button class="btn btn-warning btn-sm me-1"
                                data-bs-toggle="modal"
                                data-bs-target="#editUser{{ $user->id }}">
                            Edit
                        </button>

                        <form method="POST"
                              action="{{ route('admin.users.destroy', $user) }}"
                              class="d-inline"
                              onsubmit="return confirm('Delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>

                {{-- EDIT MODAL --}}
<div class="modal fade"
     id="editUser{{ $user->id }}"
     tabindex="-1">

    <div class="modal-dialog">
        <div class="modal-content">

            {{-- MAIN UPDATE FORM --}}
            <form method="POST"
                  action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">
                        Update User Profile
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ $user->name }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ $user->email }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}"
                                    {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Faculty</label>
                        <select name="faculty_id" class="form-select">
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}"
                                    {{ $user->faculty_id == $faculty->id ? 'selected' : '' }}>
                                    {{ $faculty->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-success">
                        Save Changes
                    </button>

                </div>

            </form>

            {{-- SEPARATE RESET PASSWORD FORM --}}
            <div class="px-3 pb-3">
                <form method="POST"
                      action="{{ route('admin.users.reset-password', $user) }}">
                    @csrf

                    <button type="submit"
                            class="btn btn-warning w-100"
                            onclick="return confirm('Reset this user password?')">
                        Reset User Password
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>

            @empty
                <tr>
                    <td colspan="6" class="text-center">
                        No users found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        <div class="pagination justify-content-center">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>

</div>

<script>
let timer;
document.getElementById('searchInput').addEventListener('keyup', function() {
    clearTimeout(timer);
    timer = setTimeout(() => {
        document.getElementById('searchForm').submit();
    }, 600);
});
</script>

</x-app-layout>