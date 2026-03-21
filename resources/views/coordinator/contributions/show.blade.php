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
            <a href="{{ route('contributions.download', $contribution->id) }}"
               class="btn btn-outline-primary btn-sm">
                Download Document
            </a>

            @if($contribution->images->count())
                <hr>
                <h6>Uploaded Images</h6>

                <div class="row g-3">
                    @foreach($contribution->images as $image)
                        <div class="col-md-3">
                            <div class="card shadow-sm p-2">

                                <img src="{{ asset('storage/'.$image->image_path) }}"
                                     class="img-fluid rounded"
                                     style="height:200px; object-fit:cover; cursor:pointer;"
                                     title="{{ $image->alt_text }}"
                                     data-bs-toggle="tooltip"

                                     onclick="openImageModal(
                                        '{{ asset('storage/'.$image->image_path) }}',
                                        `{{ addslashes($image->alt_text) }}`
                                     )">
                            </div>
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

{{-- ========================= --}}
{{-- MODAL --}}
{{-- ========================= --}}
<div id="imageModal" class="image-modal">

    <div class="modal-box">

        <div class="modal-header">
            <h5 class="modal-title">Image Preview</h5>
            <span class="close-btn" onclick="closeImageModal()">&times;</span>
        </div>

        <div class="modal-body text-center">
            <img id="modalImage" class="modal-img">

            <div class="modal-desc mt-3">
                <strong>Image Description:</strong>
                <p id="modalDescription" class="mb-0"></p>
            </div>
        </div>

    </div>

</div>

<style>
.image-modal {
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease;
}

.image-modal.show {
    opacity: 1;
    visibility: visible;
}

.modal-box {
    background: #fff;
    width: 500px;
    max-width: 90%;
    border-radius: 10px;
    overflow: hidden;
    transform: scale(0.9);
    transition: transform 0.3s ease;
}

.image-modal.show .modal-box {
    transform: scale(1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #ddd;
}

.modal-body {
    padding: 20px;
}

.modal-img {
    width: 100%;
    max-height: 300px;
    object-fit: contain;
}

.close-btn {
    font-size: 22px;
    cursor: pointer;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });
});

function openImageModal(src, description) {
    const modal = document.getElementById("imageModal");

    document.getElementById("modalImage").src = src;
    document.getElementById("modalDescription").innerText = description;

    modal.classList.add("show");
    document.body.style.overflow = "hidden";
}

function closeImageModal() {
    const modal = document.getElementById("imageModal");

    modal.classList.remove("show");
    document.body.style.overflow = "auto";
}

window.onclick = function(event) {
    const modal = document.getElementById("imageModal");
    if (event.target === modal) {
        closeImageModal();
    }
}
</script>

</x-app-layout>