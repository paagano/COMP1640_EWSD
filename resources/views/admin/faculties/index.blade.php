<x-app-layout>
<div class="container py-4">

    <h2 class="mb-3">Manage Faculties</h2>
    <hr>

    {{-- SUCCESS / ERROR --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    {{-- ===================================== --}}
    {{-- COLLAPSIBLE ADD NEW FACULTY --}}
    {{-- ===================================== --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-light d-flex justify-content-between align-items-center"
             style="cursor:pointer;"
             data-bs-toggle="collapse"
             data-bs-target="#addFacultyCollapse">

            <h5 class="mb-0">Add New Faculty</h5>
            <span class="text-muted">⬇</span>
        </div>

        <div id="addFacultyCollapse" class="collapse">
            <div class="card-body">

                <form method="POST" action="{{ route('admin.faculties.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Faculty Name</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-success me-2">
                            Add Faculty
                        </button>

                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-toggle="collapse"
                                data-bs-target="#addFacultyCollapse">
                            Cancel
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>


    {{-- ============================= --}}
    {{-- FACULTY LIST --}}
    {{-- ============================= --}}
    <h4>Faculty List</h4>

    {{-- INTELLIGENT SEARCH --}}
    <div class="mb-3">
        <form method="GET" id="searchForm">
            <div class="input-group" style="max-width: 400px;">

                {{-- <span class="input-group-text">🔍</span> --}}

                <input type="text"
                       name="search"
                       id="searchInput"
                       class="form-control"
                       placeholder="Search by name, ID, users or contributions..."
                       value="{{ request('search') }}">

                @if(request('search'))
                    <a href="{{ route('admin.faculties.index') }}"
                       class="btn btn-outline-secondary">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th style="width:8%">ID</th>
                <th style="width:32%">Faculty Name</th>
                <th style="width:15%">Users</th>
                <th style="width:15%">Contributions</th>
                <th style="width:20%">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($faculties as $faculty)
                <tr>
                    <td>{{ $faculty->id }}</td>
                    <td>{{ $faculty->name }}</td>
                    <td>{{ $faculty->users_count }}</td>
                    <td>{{ $faculty->contributions_count }}</td>

                    <td>
                        <button class="btn btn-warning btn-sm me-1"
                                data-bs-toggle="modal"
                                data-bs-target="#editFaculty{{ $faculty->id }}">
                            Edit
                        </button>

                        <form method="POST"
                              action="{{ route('admin.faculties.destroy', $faculty) }}"
                              class="d-inline"
                              onsubmit="return confirm('Delete this faculty?');">
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
                     id="editFaculty{{ $faculty->id }}"
                     tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <form method="POST"
                                  action="{{ route('admin.faculties.update', $faculty) }}">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Faculty</h5>
                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <label class="form-label">Faculty Name</label>
                                    <input type="text"
                                           name="name"
                                           class="form-control"
                                           value="{{ $faculty->name }}"
                                           required>
                                </div>

                                <div class="modal-footer">
                                    <button type="button"
                                            class="btn btn-secondary"
                                            data-bs-dismiss="modal">
                                        Cancel
                                    </button>
                                    <button class="btn btn-success">
                                        Update
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="5" class="text-center">
                        No faculties found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- PAGINATION --}}
    <div class="mt-3">
        {{ $faculties->withQueryString()->links() }}
    </div>

</div>


{{-- ============================= --}}
{{-- LIVE SEARCH SCRIPT --}}
{{-- ============================= --}}
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