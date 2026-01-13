<head>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="mb-3">
    <label for="name" class="form-label">Product Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" 
           id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control @error('description') is-invalid @enderror" 
              id="description" name="description" rows="3" required>{{ old('description', $product->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="condition" class="form-label">Condition</label>
    <select class="form-select @error('condition') is-invalid @enderror" 
            id="condition" name="condition" required>
        <option value="">Select condition...</option>
        @foreach(['New', 'Like New', 'Good', 'Fair', 'Poor'] as $condition)
            <option value="{{ $condition }}" {{ (old('condition', $product->condition ?? '') == $condition) ? 'selected' : '' }}>
                {{ $condition }}
            </option>
        @endforeach
    </select>
    @error('condition')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="mrp" class="form-label">Price</label>
    <div class="input-group">
        <span class="input-group-text">$</span>
        <input type="number" step="0.01" class="form-control @error('mrp') is-invalid @enderror" 
               id="mrp" name="mrp" value="{{ old('mrp', $product->mrp ?? '') }}" required>
    </div>
    @error('mrp')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Product Images -->
<div class="mb-3">
    <label class="form-label">{{ isset($product) ? 'Add More Photos' : 'Product Photos' }} (Max 5)</label>
    
    <div class="mb-2">
        <div>
            <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                id="images" name="images[]" multiple accept="image/*">
            <div class="d-flex justify-content-between mt-1">
                <div class="form-text">Upload up to 5 photos total. Maximum file size: 2MB each.</div>
                <small class="text-muted" id="image-count-info">
                    @php
                        $existingCount = 0;
                        if(isset($product) && ($product->photo || $product->photos)) {
                            $photos = $product->photos ?? $product->photo ?? null;
                            if (!is_array($photos)) {
                                $photos = json_decode($photos, true) ?: [];
                            }
                            $existingCount = count($photos);
                        }
                    @endphp
                    {{ $existingCount }}/5 images in database, 0 new selected ({{ $existingCount }}/5 total)
                </small>
            </div>
            @error('images.*')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<!-- Image Preview Section -->
@if(isset($product) && ($product->photo || $product->photos))
    <div class="mb-3">
        <label class="form-label">Current Photos</label>
        <div class="row">
            @php
                // Handle both photo and photos columns for compatibility
                $photos = $product->photos ?? $product->photo ?? null;
                if (!is_array($photos)) {
                    $photos = json_decode($photos, true) ?: [];
                }
            @endphp

            @foreach($photos as $photo)
                <div class="col-md-2 mb-2">
                    <div class="position-relative">
                        <img src="{{ asset($photo) }}" class="img-thumbnail" alt="Product Photo">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-photo" 
                                data-photo="{{ $photo }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        <input type="hidden" name="removed_photos" id="removed-photos" value="">
    </div>
@endif

<!-- Image Preview for new uploads -->
<div class="mb-3">
    <label class="form-label" id="new-images-label" style="display: none;">Newly Added Images</label>
    <div id="image-preview" class="row"></div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image upload handling
        const imageInput = document.getElementById('images');
        const imagePreview = document.getElementById('image-preview');
        const imageCountInfo = document.getElementById('image-count-info');
        const newImagesLabel = document.getElementById('new-images-label');
        const maxImages = 5;
        let removedPhotos = [];
        let selectedFiles = new DataTransfer(); // To manage selected files
        
        // Track existing photos if any
        let existingPhotoCount = 0;
        @if(isset($product) && ($product->photo || $product->photos))
            @php
                $photos = $product->photos ?? $product->photo ?? null;
                if (!is_array($photos)) {
                    $photos = json_decode($photos, true) ?: [];
                }
                echo "existingPhotoCount = " . count($photos) . ";";
            @endphp
        @endif
        
        // Function to update image count display with visual feedback
        function updateImageCountInfo() {
            const totalExisting = existingPhotoCount - removedPhotos.length;
            const totalNew = selectedFiles.files.length;
            const totalImages = totalExisting + totalNew;
            
            // Update the displayed count with more visual emphasis
            imageCountInfo.innerHTML = `<strong>${totalExisting}</strong>/5 images in database, <strong>${totalNew}</strong> new selected (<strong>${totalImages}</strong>/5 total)`;
            
            // Provide visual feedback on count change
            imageCountInfo.classList.add('text-primary');
            setTimeout(() => {
                imageCountInfo.classList.remove('text-primary');
            }, 500);
            
            // Call the function to update input state
            updateImageInputState(totalImages);
        }
        
        // Function to disable/enable image input based on count
        function updateImageInputState(totalImages) {
            if (totalImages >= maxImages) {
                // Disable the input and add visual indication
                imageInput.disabled = true;
                imageInput.classList.add('disabled-input');
                imageInput.parentElement.querySelector('.form-text').innerHTML = 
                    `Maximum limit of ${maxImages} images reached.`;
                imageInput.parentElement.querySelector('.form-text').classList.remove('form-text');
                imageInput.parentElement.querySelector('.form-text, .text-danger').classList.add('text-danger', 'fw-bold');
            } else {
                // Enable the input
                imageInput.disabled = false;
                imageInput.classList.remove('disabled-input');
                imageInput.parentElement.querySelector('.text-danger, .fw-bold').classList.remove('text-danger', 'fw-bold');
                imageInput.parentElement.querySelector('div').classList.add('form-text');
                imageInput.parentElement.querySelector('.form-text').innerHTML = 
                    `Upload up to ${maxImages} photos total. Maximum file size: 2MB each.`;
            }
        }
        
        // Function to refresh preview
        function refreshImagePreviews() {
            imagePreview.innerHTML = '';
            
            if(selectedFiles.files.length > 0) {
                newImagesLabel.style.display = 'block';
            } else {
                newImagesLabel.style.display = 'none';
            }
            
            Array.from(selectedFiles.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-2 mb-2';
                    
                    const wrapper = document.createElement('div');
                    wrapper.className = 'position-relative';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail';
                    img.alt = 'Image Preview';
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    removeBtn.dataset.index = index;
                    
                    removeBtn.addEventListener('click', function() {
                        // Create a new DataTransfer object
                        const newFiles = new DataTransfer();
                        
                        // Add all files except the one to remove
                        Array.from(selectedFiles.files)
                            .filter((_, i) => i !== parseInt(this.dataset.index))
                            .forEach(file => newFiles.items.add(file));
                        
                        // Update the selectedFiles object
                        selectedFiles = newFiles;
                        
                        // Update the actual file input
                        imageInput.files = selectedFiles.files;
                        
                        // Refresh previews
                        refreshImagePreviews();
                        
                        // Update image count
                        updateImageCountInfo();
                    });
                    
                    wrapper.appendChild(img);
                    wrapper.appendChild(removeBtn);
                    col.appendChild(wrapper);
                    imagePreview.appendChild(col);
                }
                reader.readAsDataURL(file);
            });
        }
        
        // Handle file selection
        imageInput.addEventListener('change', function() {
            // Check if the FileList has items to add
            if (this.files.length > 0) {
                // Check if total number of images would exceed maximum
                const totalImagesAfterAdd = existingPhotoCount - removedPhotos.length + selectedFiles.files.length + this.files.length;
                
                if (totalImagesAfterAdd > maxImages) {
                    alert(`You can only upload a maximum of ${maxImages} images in total. You currently have ${existingPhotoCount - removedPhotos.length} existing and ${selectedFiles.files.length} new images.`);
                    return;
                }
                
                // Add new files to our collection
                Array.from(this.files).forEach(file => {
                    selectedFiles.items.add(file);
                });
                
                // Reset the file input
                this.value = '';
                
                // Refresh previews
                refreshImagePreviews();
                
                // Update image count
                updateImageCountInfo();
            }
        });
        
        // Handle removal of existing photos
        const removePhotoButtons = document.querySelectorAll('.remove-photo');
        const removedPhotosInput = document.getElementById('removed-photos');
        
        removePhotoButtons.forEach(button => {
            button.addEventListener('click', function() {
                const photoPath = this.getAttribute('data-photo');
                
                // Add to removed photos list if not already included
                if (!removedPhotos.includes(photoPath)) {
                    removedPhotos.push(photoPath);
                    removedPhotosInput.value = JSON.stringify(removedPhotos);
                    
                    // Remove the preview
                    this.closest('.col-md-2').remove();
                    
                    // Update existing photo count and display with animation
                    updateImageCountInfo();
                    
                    console.log(`Photo removed. Removed count: ${removedPhotos.length}, Remaining: ${existingPhotoCount - removedPhotos.length}`);
                }
            });
        });
        
        // Form validation
        const form = imageInput.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const totalImages = existingPhotoCount - removedPhotos.length + selectedFiles.files.length;
                
                if (totalImages === 0) {
                    e.preventDefault();
                    alert('Please add at least one image');
                    return false;
                }
                
                if (totalImages > maxImages) {
                    e.preventDefault();
                    alert(`You can only upload a maximum of ${maxImages} images in total.`);
                    return false;
                }
                
                // Make sure the file input has the files we want to upload
                if (selectedFiles.files.length > 0) {
                    // Create a new input element to replace the original
                    const hiddenFileInputs = document.createElement('div');
                    hiddenFileInputs.style.display = 'none';
                    
                    // Create individual file inputs for each file to ensure upload works
                    Array.from(selectedFiles.files).forEach((file, index) => {
                        const fileInput = document.createElement('input');
                        fileInput.type = 'file';
                        fileInput.name = 'images[]';
                        
                        // Convert File object to a DataTransfer to attach to input
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        fileInput.files = dataTransfer.files;
                        
                        hiddenFileInputs.appendChild(fileInput);
                    });
                    
                    // Hide the original file input
                    imageInput.style.display = 'none';
                    
                    // Append new inputs to the form
                    form.appendChild(hiddenFileInputs);
                    
                    // Allow form submission to continue
                    return true;
                }
                
                // Debug output before submission
                console.log(`Form submission - Existing: ${existingPhotoCount}, Removed: ${removedPhotos.length}, New: ${selectedFiles.files.length}, Total: ${existingPhotoCount - removedPhotos.length + selectedFiles.files.length}`);
            });
        }
        
        // Initialize image count with initial values
        console.log(`Initial state - Existing photos: ${existingPhotoCount}`);
        updateImageCountInfo();
        
        // Add some CSS for the disabled state
        const style = document.createElement('style');
        style.textContent = `.disabled-input { background-color: #e9ecef; cursor: not-allowed; }`;
        document.head.appendChild(style);
    });
</script>
@endpush
