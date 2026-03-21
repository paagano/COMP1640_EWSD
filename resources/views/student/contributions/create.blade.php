<x-app-layout>
<div class="container py-4">


    <div class="mb-4">
        <h2 class="fw-semibold mb-1">New Contribution</h2>
        <small class="text-muted">
            Submit your article for review and publication consideration.
        </small>
    </div>

 
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="POST"
                  action="{{ route('student.contributions.store') }}"
                  enctype="multipart/form-data">

                @csrf

                {{-- Title --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Article Title</label>
                    <input type="text"
                           name="title"
                           class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title') }}"
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Summary --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Content Summary</label>
                    <textarea name="content_summary"
                              rows="4"
                              class="form-control @error('content_summary') is-invalid @enderror"
                              required>{{ old('content_summary') }}</textarea>
                    @error('content_summary')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Word Document --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Upload Word Document
                    </label>

                    <input type="file"
                           name="word_document"
                           class="form-control @error('word_document') is-invalid @enderror"
                           required>
                    
                    <div class="text-muted small mb-1">
                        Allowed file types: .DOC, .DOCX | Max size: 10MB
                    </div>

                    @error('word_document')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Images --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Upload Supporting Images (Optional)
                    </label>
      
                    <input type="file"
                           id="images"
                           name="images[]"
                           multiple
                           accept="image/*"
                           class="form-control @error('images.*') is-invalid @enderror">

                    <div class="text-muted small mb-1">
                        Supported formats: .JPG, .JPEG, .PNG, .GIF | Max 5 images | Max 5MB each | Recommended dimensions: 1200x800px 
                    </div>

                    @error('images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- DYNAMIC IMAGE DESCRIPTIONS --}}
                <div id="imageDescriptionsContainer" class="mb-3 d-none">
                    <label class="form-label fw-semibold">
                        Image Descriptions <span class="text-danger">*</span>
                    </label>

                    <div id="descriptionsWrapper"></div>

                    <div class="text-muted small">
                        This description helps visually impaired users understand the image attached. Please help make the website accessible to everyone by providing a clear and concise description.
                    </div>
                </div>

                {{-- Terms --}}
                <div class="form-check mb-4">
                    <input type="checkbox"
                           name="agreed_terms"
                           class="form-check-input @error('agreed_terms') is-invalid @enderror"
                           required>

                    <label class="form-check-label">
                        I confirm that I agree to the
                        <a href="#"
                           data-bs-toggle="modal"
                           data-bs-target="#termsModal">
                           Terms and Conditions
                        </a>
                        and that this work is my original submission.
                    </label>

                    @error('agreed_terms')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('student.contributions.index') }}"
                       class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-success px-4">
                        Submit Contribution
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@include('components.terms-modal')

{{-- JAVASCRIPT --}}
<script>
    const imageInput = document.getElementById('images');
    const container = document.getElementById('imageDescriptionsContainer');
    const wrapper = document.getElementById('descriptionsWrapper');

    imageInput.addEventListener('change', function () {

        wrapper.innerHTML = '';

        if (this.files.length > 0) {

            if (this.files.length > 5) {
                alert('Maximum 5 images allowed.');
                this.value = '';
                container.classList.add('d-none');
                return;
            }

            container.classList.remove('d-none');

            Array.from(this.files).forEach((file, index) => {

                const sizeMB = file.size / (1024 * 1024);

                if (sizeMB > 5) {
                    alert(`"${file.name}" exceeds 5MB.`);
                    imageInput.value = '';
                    container.classList.add('d-none');
                    wrapper.innerHTML = '';
                    return;
                }

                const div = document.createElement('div');
                div.classList.add('mb-2');

                div.innerHTML = `
                    <label class="form-label small fw-semibold">
                        Description for ${file.name}
                    </label>
                    <textarea name="alt_texts[]"
                              class="form-control"
                              rows="2"
                              required></textarea>
                `;

                wrapper.appendChild(div);
            });

        } else {
            container.classList.add('d-none');
        }
    });
</script>

</x-app-layout>