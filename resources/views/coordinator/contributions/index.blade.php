<x-app-layout>

<style>

/* Pagination layout */
.pagination-container{
display:flex;
justify-content:space-between;
align-items:center;
margin-top:20px;
}

/* Results text */
.results-count{
font-size:14px;
color:#6c757d;
}

/* Pagination buttons */
.pagination{
margin:0;
}

.pagination .page-link{
padding:6px 12px;
font-size:14px;
color:#0d6efd;
border:1px solid #dee2e6;
}

.pagination .page-item.active .page-link{
background:#0d6efd;
border-color:#0d6efd;
color:#fff;
}

.pagination .page-link:hover{
background:#e9ecef;
}

/* Reduce arrow icon size */
.pagination svg{
width:14px !important;
height:14px !important;
}

/* Hide Laravel duplicate results text */
.pagination-container nav > div:first-child > div:first-child{
display:none !important;
}

</style>


<div class="container py-4">

    {{-- HEADER --}}
    <h2 class="fw-semibold mb-1">Faculty Contributions</h2>

    <br>

    <div class="d-flex justify-content-between align-items-center mb-4">

        <p class="text-muted mb-0">
            <strong>
                {{ Auth::user()->faculty->name ?? 'Not Assigned' }}
            </strong>
        </p>

        <a href="{{ route('coordinator.dashboard') }}"
           class="btn btn-outline-secondary">
            ← Back to Dashboard
        </a>

    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    {{-- SEARCH + FILTER --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <form method="GET" action="{{ route('coordinator.contributions.index') }}">

                <div class="row g-3 align-items-end">

                    <div class="col-md-2">
                        <label class="form-label small text-muted">Search</label>
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Search title or student..."
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small text-muted">Title</label>
                        <input type="text"
                               name="title"
                               class="form-control"
                               placeholder="Filter by Title"
                               value="{{ request('title') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small text-muted">Student</label>
                        <input type="text"
                               name="student"
                               class="form-control"
                               placeholder="Filter by Student"
                               value="{{ request('student') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small text-muted">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="submitted" {{ request('status')=='submitted'?'selected':'' }}>Submitted</option>
                            <option value="commented" {{ request('status')=='commented'?'selected':'' }}>Reviewed</option>
                            <option value="selected" {{ request('status')=='selected'?'selected':'' }}>Selected</option>
                            <option value="published" {{ request('status')=='published'?'selected':'' }}>Published</option>
                            <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small text-muted">Submitted Date</label>
                        <input type="date"
                               name="date"
                               class="form-control"
                               value="{{ request('date') }}">
                    </div>

                    <div class="col-md-2 d-flex gap-2">

                        <button class="btn btn-outline-primary flex-fill">
                            Apply
                        </button>

                        <a href="{{ route('coordinator.contributions.index') }}"
                           class="btn btn-outline-secondary flex-fill">
                            Clear
                        </a>

                    </div>

                </div>

            </form>

        </div>
    </div>


    {{-- CONTRIBUTIONS TABLE --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:15%">Title</th>
                            <th style="width:25%">Content Summary</th>
                            <th style="width:15%">Student</th>
                            <th style="width:15%">Status</th>
                            <th style="width:15%">Submitted</th>
                            <th style="width:15%" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($contributions as $contribution)
                            <tr>

                                <td>
                                    <strong>{{ $contribution->title }}</strong>
                                </td>

                                <td>
                                    @if(!empty($contribution->content_summary))
                                        {{ \Illuminate\Support\Str::limit($contribution->content_summary, 120) }}
                                    @else
                                        <span class="text-muted fst-italic">
                                            No summary provided
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $contribution->student->name }}
                                </td>

                                <td>
                                    @if($contribution->status === 'submitted')
                                        <span class="badge bg-secondary">Submitted</span>
                                    @elseif($contribution->status === 'commented')
                                        <span class="badge bg-warning text-dark">Reviewed</span>
                                    @elseif($contribution->status === 'selected')
                                        <span class="badge bg-info text-dark">Selected</span>
                                    @elseif($contribution->status === 'published')
                                        <span class="badge bg-success">Published</span>
                                    @elseif($contribution->status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $contribution->created_at->format('d M Y') }}
                                </td>

                                <td class="text-center">
                                    @if($contribution->status === 'published')
                                        <button class="btn btn-sm btn-secondary disabled opacity-75">
                                            Review
                                        </button>
                                    @else
                                        <a href="{{ route('coordinator.contributions.show', $contribution) }}"
                                           class="btn btn-sm btn-primary">
                                            Review
                                        </a>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-3">
                                    No submissions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>


    {{-- PAGINATION --}}
    @if ($contributions->hasPages())

    <div class="pagination-container">

        <div class="results-count">
            Showing {{ $contributions->firstItem() }}
            to {{ $contributions->lastItem() }}
            of {{ $contributions->total() }} results
        </div>

        <div>
            {{ $contributions->withQueryString()->links('pagination::bootstrap-5') }}
        </div>

    </div>

    @endif

</div>
</x-app-layout>