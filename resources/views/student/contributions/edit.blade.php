<x-app-layout>
<div class="container py-4">

    <h2 class="fw-semibold mb-4">Edit Contribution</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="POST"
                  action="{{ route('student.contributions.update', $contribution->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- TITLE --}}
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text"
                           name="title"
                           class="form-control"
                           value="{{ old('title', $contribution->title) }}"
                           required>
                </div>

                {{-- CONTENT SUMMARY --}}
                <div class="mb-3">
                    <label class="form-label">Content Summary</label>
                    <textarea name="content_summary"
                              class="form-control"
                              rows="4"
                              required>{{ old('content_summary', $contribution->content_summary) }}</textarea>
                </div>

                {{-- WORD DOCUMENT --}}
                <div class="mb-4">
                    <label class="form-label">Replace Word Document (Optional)</label>
                    <input type="file"
                           name="word_document"
                           class="form-control">
                </div>

                {{-- ========================= --}}
                {{-- EXISTING IMAGES --}}
                {{-- ========================= --}}
                @if($contribution->images && $contribution->images->count() > 0)
                    <h5 class="mb-3">Replace Images (Drag to reorder)</h5>

                    <div id="sortableImages" class="row g-3 mb-4">
                        @foreach($contribution->images->sortBy('order') as $image)
                            <div class="col-md-3 sortable-item" data-id="{{ $image->id }}">
                                <div class="card shadow-sm p-2">

                                    <img src="{{ asset('storage/'.$image->image_path) }}"
                                         class="img-fluid rounded mb-2"
                                         style="height:180px; object-fit:cover;">

                                    <small class="text-muted mb-2">
                                        {{ $image->alt_text }}
                                    </small>

                                    {{-- Replace --}}
                                    <input type="file"
                                           name="replace_images[{{ $image->id }}]"
                                           class="form-control form-control-sm mb-2 replace-image-input"
                                           data-id="{{ $image->id }}">

                                    {{-- Replace Description --}}
                                    <div class="mb-2 d-none"
                                         id="replace-desc-{{ $image->id }}">
                                        <textarea name="replace_alt_text[{{ $image->id }}]"
                                                  class="form-control form-control-sm"
                                                  placeholder="Describe new image..."></textarea>
                                    </div>

                                    {{-- Delete --}}
                                    <div class="form-check">
                                        <input type="checkbox"
                                               name="delete_images[]"
                                               value="{{ $image->id }}"
                                               class="form-check-input delete-checkbox">
                                        <label class="form-check-label text-danger small">
                                            Remove
                                        </label>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>

                    <input type="hidden" name="image_order" id="imageOrderInput">
                @endif

                {{-- ========================= --}}
                {{-- NEW IMAGES --}}
                {{-- ========================= --}}
                <div class="mb-4">
                    <label class="form-label">Upload Additional Images (Max 5)</label>

                    <input type="file"
                           id="newImages"
                           name="images[]"
                           multiple
                           accept="image/*"
                           class="form-control">

                    <div id="previewContainer" class="row mt-3"></div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="text-end">
                    <a href="{{ route('student.contributions.index') }}"
                       class="btn btn-secondary me-2">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        Update Contribution
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

{{-- SORTABLE --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>

// ---------------------------
// REORDER
// ---------------------------
const sortable = new Sortable(document.getElementById('sortableImages'), {
    animation: 150,
    onEnd: updateOrder
});

function updateOrder() {
    let order = [];
    document.querySelectorAll('.sortable-item').forEach(el => {
        order.push(el.dataset.id);
    });
    document.getElementById('imageOrderInput').value = order.join(',');
}

// ---------------------------
// REPLACE IMAGE (preview + confirm)
// ---------------------------
document.querySelectorAll('.replace-image-input').forEach(input => {
    input.addEventListener('change', function () {

        const id = this.dataset.id;
        const desc = document.getElementById('replace-desc-' + id);
        const card = this.closest('.card');

        if (this.files.length > 0) {

            if (!confirm("Are you sure you want to replace this image?")) {
                this.value = '';
                return;
            }

            desc.classList.remove('d-none');
            desc.querySelector('textarea').setAttribute('required', 'required');

            const reader = new FileReader();
            reader.onload = function (e) {
                card.querySelector('img').src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
});

// ---------------------------
// DELETE CONFIRM
// ---------------------------
document.querySelectorAll('.delete-checkbox').forEach(cb => {
    cb.addEventListener('change', function () {
        if (this.checked) {
            if (!confirm("Are you sure you want to delete this image?")) {
                this.checked = false;
            }
        }
    });
});

// ---------------------------
// NEW IMAGE PREVIEW
// ---------------------------
const input = document.getElementById('newImages');
const preview = document.getElementById('previewContainer');

input.addEventListener('change', function () {

    preview.innerHTML = '';

    if (this.files.length > 5) {
        alert('Maximum 5 images allowed');
        this.value = '';
        return;
    }

    Array.from(this.files).forEach((file) => {

        if (file.size > 5 * 1024 * 1024) {
            alert(file.name + ' exceeds 5MB');
            input.value = '';
            preview.innerHTML = '';
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {

            const div = document.createElement('div');
            div.classList.add('col-md-3');

            div.innerHTML = `
                <div class="card p-2 shadow-sm">
                    <img src="${e.target.result}" class="img-fluid mb-2" style="height:150px;object-fit:cover;">
                    <textarea name="alt_texts[]" class="form-control form-control-sm" placeholder="Image description..." required></textarea>
                </div>
            `;

            preview.appendChild(div);
        };

        reader.readAsDataURL(file);
    });
});

</script>

</x-app-layout>