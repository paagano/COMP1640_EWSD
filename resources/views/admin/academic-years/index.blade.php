<x-app-layout>
<div class="container py-4">

    <h2 class="mb-3">Manage Academic Years</h2>
    <hr>

    {{-- SUCCESS / ERROR --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


    {{-- ===================================== --}}
    {{-- COLLAPSIBLE ADD NEW ACADEMIC YEAR --}}
    {{-- ===================================== --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-light d-flex justify-content-between align-items-center"
             style="cursor:pointer;"
             data-bs-toggle="collapse"
             data-bs-target="#addAcademicYearCollapse">

            <h5 class="mb-0">Add New Academic Year</h5>
            <span class="text-muted">⬇</span>
        </div>

        <div id="addAcademicYearCollapse" class="collapse">
            <div class="card-body">

                <form method="POST" action="{{ route('admin.academic-years.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Year Name (e.g. 2025/2026)</label>
                        <input type="text"
                               name="year_name"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Submission Closure Date</label>
                        <input type="date"
                               name="submission_closure_date"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Final Closure Date</label>
                        <input type="date"
                               name="final_closure_date"
                               class="form-control"
                               required>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               class="form-check-input"
                               id="activeCheck">
                        <label class="form-check-label" for="activeCheck">
                            Set as Active
                        </label>
                    </div>

                    <button class="btn btn-success me-2">Add Academic Year</button>
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-toggle="collapse"
                            data-bs-target="#addAcademicYearCollapse">
                        Cancel
                    </button>

                </form>

            </div>
        </div>

    </div>


    {{-- ===================================== --}}
    {{-- ACADEMIC YEAR LIST --}}
    {{-- ===================================== --}}
    <h4>Academic Year List</h4>

    {{-- INTELLIGENT SEARCH --}}
    <form method="GET" id="searchForm" class="mb-3">
        <div class="input-group" style="max-width: 450px;">
            {{-- <span class="input-group-text">🔍</span> --}}
            <input type="text"
                   name="search"
                   id="searchInput"
                   class="form-control"
                   placeholder="Search by year name, ID or status..."
                   value="{{ request('search') }}">
            @if(request('search'))
                <a href="{{ route('admin.academic-years.index') }}"
                   class="btn btn-outline-secondary">Clear</a>
            @endif
        </div>
    </form>

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th style="width:8%">ID</th>
                <th style="width:20%">Year</th>
                <th style="width:20%">Submission Closure Date</th>
                <th style="width:20%">Final Closure Date</th>
                <th style="width:12%">Status</th>
                <th style="width:20%">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($academicYears as $year)
                <tr>
                    <td>{{ $year->id }}</td>
                    <td>{{ $year->year_name }}</td>
                    <td>{{ $year->submission_closure_date }}</td>
                    <td>{{ $year->final_closure_date }}</td>

                    <td>
                        <form method="POST"
                              action="{{ route('admin.academic-years.toggle-status', $year) }}">
                            @csrf
                            @method('PUT')

                            <button class="btn btn-sm {{ $year->is_active ? 'btn-success' : 'btn-secondary' }}">
                                {{ $year->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>

                    <td>
                        <button class="btn btn-warning btn-sm me-1"
                                data-bs-toggle="modal"
                                data-bs-target="#editYear{{ $year->id }}">
                            Edit
                        </button>

                        <form method="POST"
                              action="{{ route('admin.academic-years.destroy', $year) }}"
                              class="d-inline"
                              onsubmit="return confirm('Delete this academic year?')">
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
                     id="editYear{{ $year->id }}"
                     tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <form method="POST"
                                  action="{{ route('admin.academic-years.update', $year) }}">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Academic Year</h5>
                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    <div class="mb-3">
                                        <label class="form-label">Year Name</label>
                                        <input type="text"
                                               name="year_name"
                                               class="form-control"
                                               value="{{ $year->year_name }}"
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Submission Closure Date</label>
                                        <input type="date"
                                               name="submission_closure_date"
                                               class="form-control"
                                               value="{{ $year->submission_closure_date }}"
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Final Closure Date</label>
                                        <input type="date"
                                               name="final_closure_date"
                                               class="form-control"
                                               value="{{ $year->final_closure_date }}"
                                               required>
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox"
                                               name="is_active"
                                               value="1"
                                               class="form-check-input"
                                               {{ $year->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            Active
                                        </label>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="button"
                                            class="btn btn-secondary"
                                            data-bs-dismiss="modal">
                                        Cancel
                                    </button>
                                    <button class="btn btn-success">
                                        Save Changes
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="6" class="text-center">
                        No academic years found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $academicYears->withQueryString()->links() }}
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