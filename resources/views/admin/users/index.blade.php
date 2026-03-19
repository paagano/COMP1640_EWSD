<x-app-layout>

    <style>
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

                <h5 class="mb-0">Create New User</h5>
                <span class="text-muted">⬇</span>
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
                                    <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button class="btn btn-success me-2">Add User</button>
                        <button type="button" class="btn btn-secondary"
                                data-bs-toggle="collapse"
                                data-bs-target="#createUserCollapse">
                            Cancel
                        </button>

                    </form>

                </div>
            </div>

        </div>

        {{-- USER LIST --}}
        <h4>User List</h4>

        {{-- SEARCH --}}
        <form method="GET" id="searchForm" class="mb-3">
            <div class="input-group" style="max-width: 450px;">
                <input type="text" name="search" id="searchInput"
                       class="form-control"
                       placeholder="Search by name, email, role or ID..."
                       value="{{ request('search') }}">
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Clear</a>
                @endif
            </div>
        </form>

        {{-- BULK ACTIONS --}}
        <div class="mb-3 d-flex gap-2">

            <form method="POST" action="{{ route('admin.users.bulk.activate') }}">
                @csrf
                <div id="bulkActivateInputs"></div>
                <button type="submit"
                        class="btn btn-success btn-sm"
                        onclick="return handleBulk(event, 'bulkActivateInputs', 'Activate selected users?')">
                    Bulk Activate
                </button>
            </form>

            <form method="POST" action="{{ route('admin.users.bulk.deactivate') }}">
                @csrf
                <div id="bulkDeactivateInputs"></div>
                <button type="submit"
                        class="btn btn-secondary btn-sm"
                        onclick="return handleBulk(event, 'bulkDeactivateInputs', 'Deactivate selected users?')">
                    Bulk Deactivate
                </button>
            </form>

            <form method="POST" action="{{ route('admin.users.bulk.delete') }}">
                @csrf
                <div id="bulkDeleteInputs"></div>
                <button type="submit"
                        class="btn btn-danger btn-sm"
                        onclick="return handleBulk(event, 'bulkDeleteInputs', 'Delete selected users permanently? This cannot be undone!')">
                    Bulk Delete
                </button>
            </form>

        </div>

        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Faculty</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td><input type="checkbox" name="user_ids[]" value="{{ $user->id }}"></td>

                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->getRoleNames()->first() }}</td>
                        <td>{{ $user->faculty->name ?? '-' }}</td>

                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>

                        <td class="d-flex flex-wrap gap-1">

                            <button class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editUser{{ $user->id }}">
                                Edit
                            </button>

                            @if($user->is_active)
                                <form method="POST" action="{{ route('admin.users.deactivate', $user) }}">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-secondary btn-sm">Deactivate</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.users.activate', $user) }}">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-success btn-sm">Activate</button>
                                </form>
                            @endif

                            <form method="POST"
                                  action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone!')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </form>

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="8" class="text-center">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- MODALS --}}
        @foreach($users as $user)
        <div class="modal fade" id="editUser{{ $user->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    {{-- UPDATE USER FORM --}}
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title">Update User Profile</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ $user->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ $user->email }}" required>
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

                    {{-- 🔥 RESET PASSWORD (FULL WIDTH BUTTON) --}}
                    <div class="px-3 pb-3">
                        <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
                            @csrf
                            <button type="submit"
                                    class="btn btn-warning w-100"
                                    onclick="return confirm('Reset this user\\'s password? A temporary password will be sent via email.')">
                                Reset User Password
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        @endforeach

        <div class="mt-3">
            <div class="pagination justify-content-center">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>

    </div>

    <script>
        document.getElementById('selectAll').addEventListener('change', function () {
            let checkboxes = document.querySelectorAll('input[name="user_ids[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        function handleBulk(event, containerId, message) {

            if (!confirm(message)) {
                event.preventDefault();
                return false;
            }

            let selected = document.querySelectorAll('input[name="user_ids[]"]:checked');

            if (selected.length === 0) {
                alert('Please select at least one user.');
                event.preventDefault();
                return false;
            }

            let container = document.getElementById(containerId);
            container.innerHTML = '';

            selected.forEach(cb => {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'user_ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });

            return true;
        }
    </script>

</x-app-layout>