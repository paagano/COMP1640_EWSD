<x-app-layout>
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-semibold mb-0">My Contributions</h2>
            <small class="text-muted">View and manage your article submissions</small>
        </div>

        <div>
            <a href="{{ route('student.contributions.create') }}" class="btn btn-success">
                + New Submission
            </a>

            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                ← Back to Dashboard
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Content Summary</th>
                            <th>Status</th>
                            <th>Submitted On</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($contributions as $contribution)
                        <tr>

                            {{-- TITLE --}}
                            <td style="max-width: 250px;">
                                <div class="fw-semibold text-truncate"
                                     title="{{ $contribution->title }}">
                                    {{ $contribution->title }}
                                </div>
                            </td>

                            {{-- SUMMARY --}}
                            <td style="max-width: 300px;">
                                <div class="text-truncate"
                                     title="{{ $contribution->content_summary }}">
                                    {{ $contribution->content_summary }}
                                </div>
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if($contribution->status === 'submitted')
                                    <span class="badge bg-secondary">Submitted</span>
                                @elseif($contribution->status === 'commented')
                                    <span class="badge bg-warning text-dark">Reviewed</span>
                                @elseif($contribution->status === 'selected')
                                    <span class="badge bg-success">Selected for Publication</span>
                                @elseif($contribution->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>

                            {{-- DATE --}}
                            <td>
                                {{ $contribution->created_at->format('d M Y') }}
                            </td>

                            {{-- ACTION --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">

                                    <a href="{{ route('student.contributions.show', $contribution) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>

                                    @if(in_array($contribution->status, ['submitted', 'commented']))
                                        <a href="{{ route('student.contributions.edit', $contribution) }}"
                                           class="btn btn-sm btn-outline-warning">
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('student.contributions.destroy', $contribution) }}"
                                              onsubmit="return confirm('Are you sure you want to withdraw this submission?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-outline-danger">
                                                Withdraw
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No contributions submitted yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

<style>
/* Improve truncation */
.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Better spacing */
.table td {
    padding-top: 14px;
    padding-bottom: 14px;
}

/* Button spacing */
.btn-sm {
    min-width: 70px;
}
</style>

</x-app-layout>