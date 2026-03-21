<x-app-layout>
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-semibold mb-0">My Contributions</h2>
            <small class="text-muted">
                View and manage your article submissions
            </small>
        </div>
        <div>
                <a href="{{ route('student.contributions.create') }}"
                class="btn btn-success me-2">
                    + New Submission
                </a>
                <a href="{{ route('student.dashboard') }}"
                class="btn btn-outline-secondary">
                    ← Back to Dashboard
                </a>
        </div>


    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

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

                            @php
                                $editableStatuses = ['submitted', 'commented'];
                                $isEditable = in_array($contribution->status, $editableStatuses);
                            @endphp

                            <tr>

                                {{-- Title --}}
                                <td>
                                    <strong>{{ $contribution->title }}</strong>
                                </td>

                                {{-- Content Summary --}}
                                <td style="max-width: 250px;">
                                    @if(!empty($contribution->content_summary))
                                        {{ \Illuminate\Support\Str::limit(strip_tags($contribution->content_summary), 80) }}
                                    @else
                                        <span class="text-muted fst-italic">
                                            No summary provided
                                        </span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    @if($contribution->status === 'submitted')
                                        <span class="badge bg-secondary">Submitted</span>

                                    @elseif($contribution->status === 'commented')
                                        <span class="badge bg-warning text-dark">Reviewed</span>

                                    @elseif($contribution->status === 'selected')
                                        <span class="badge bg-info text-dark">Selected for Publication</span>

                                    @elseif($contribution->status === 'published')
                                        <span class="badge bg-success">Published</span>

                                    @elseif($contribution->status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>

                                    @else
                                        <span class="badge bg-dark text-capitalize">
                                            {{ $contribution->status }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Submitted Date --}}
                                <td>
                                    {{ $contribution->created_at->format('d M Y') }}
                                </td>

                                {{-- Actions --}}
                                <td class="text-center">

                                    {{-- View --}}
                                    <a href="{{ route('student.contributions.show', $contribution->id) }}"
                                       class="btn btn-sm btn-outline-primary me-1">
                                        View
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ $isEditable ? route('student.contributions.edit', $contribution->id) : '#' }}"
                                       class="btn btn-sm btn-outline-warning me-1 {{ !$isEditable ? 'disabled opacity-50' : '' }}">
                                        Edit
                                    </a>

                                    {{-- Withdraw --}}
                                    @if($isEditable)
                                        <form action="{{ route('student.contributions.destroy', $contribution->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to withdraw this submission?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger">
                                                Withdraw
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-outline-danger disabled opacity-50">
                                            Withdraw
                                        </button>
                                    @endif

                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
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
</x-app-layout>