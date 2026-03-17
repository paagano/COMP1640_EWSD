<x-app-layout>
<div class="container py-4">

    <h2 class="fw-semibold mb-3">Review Contribution</h2>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <h4>{{ $contribution->title }}</h4>

            <p class="text-muted">
                Submitted by {{ $contribution->student->name }}
                ({{ $contribution->student->email }})
            </p>

            <hr>

            <h6>Summary</h6>
            <p>{{ $contribution->content_summary }}</p>

            <hr>

            <h6>Word Document</h6>
            <a href="{{ asset('storage/'.$contribution->word_document_path) }}"
               target="_blank"
               class="btn btn-outline-primary btn-sm">
                Download Document
            </a>

            @if($contribution->images->count())
                <hr>
                <h6>Uploaded Images</h6>
                <div class="row">
                    @foreach($contribution->images as $image)
                        <div class="col-md-3 mb-3">
                            <img src="{{ asset('storage/'.$image->image_path) }}"
                                 class="img-fluid rounded shadow-sm">
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    <!-- Review Form -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h5 class="mb-3">Add Review</h5>

            <form method="POST"
                  action="{{ route('coordinator.contributions.updateStatus', $contribution) }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Comment</label>
                    <textarea name="comment_text"
                              rows="4"
                              class="form-control"
                              required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Update Status</label>
                    <select name="status" class="form-select" required>
                        <option value="">Select Action</option>
                        <option value="commented">Reviewed</option>
                        <option value="selected">Select for Publication</option>
                        <option value="rejected">Reject</option>
                    </select>
                </div>

                <button class="btn btn-success">
                    Submit Review
                </button>

                <a href="{{ route('coordinator.contributions.index') }}"
                   class="btn btn-outline-secondary ms-2">
                    Back
                </a>

            </form>

        </div>
    </div>

</div>
</x-app-layout>