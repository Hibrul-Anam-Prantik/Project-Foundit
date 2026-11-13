@php
    $isEdit = isset($report);
@endphp

<div class="lr-form-grid">
    <div class="lr-field">
        <label for="item_name">Item Name <span style="color:#dc2626;">*</span></label>
        <input
            type="text"
            id="item_name"
            name="item_name"
            value="{{ old('item_name', $report->item_name ?? '') }}"
            placeholder="e.g., Black Lenovo Laptop Charger"
            required
            maxlength="150"
        >
        @error('item_name')
            <div class="lr-field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="lr-field">
        <label for="category">Category <span style="color:#dc2626;">*</span></label>
        <select id="category" name="category" required>
            <option value="">Select a category</option>
            @foreach ($categories as $slug => $label)
                <option value="{{ $slug }}" @selected(old('category', $report->category ?? '') === $slug)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('category')
            <div class="lr-field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="lr-field">
        <label for="lost_date">Date Lost <span style="color:#dc2626;">*</span></label>
        <input
            type="date"
            id="lost_date"
            name="lost_date"
            max="{{ now()->toDateString() }}"
            value="{{ old('lost_date', isset($report) ? $report->lost_date->toDateString() : now()->toDateString()) }}"
            required
        >
        @error('lost_date')
            <div class="lr-field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="lr-field">
        <label for="location">Last Seen Location <span style="color:#dc2626;">*</span></label>
        <input
            type="text"
            id="location"
            name="location"
            list="campus-locations"
            value="{{ old('location', $report->location ?? '') }}"
            placeholder="e.g., UB2 Room 204 or Main Cafeteria"
            required
            maxlength="255"
        >
        <datalist id="campus-locations">
            @foreach ($locations as $loc)
                <option value="{{ $loc }}"></option>
            @endforeach
        </datalist>
        @error('location')
            <div class="lr-field-error">{{ $message }}</div>
        @enderror
    </div>

    @if ($isEdit)
        <div class="lr-field lr-field-full">
            <label for="status">Tracking Status <span style="color:#dc2626;">*</span></label>
            <select id="status" name="status" required>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $report->status) === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <div class="lr-field-error">{{ $message }}</div>
            @enderror
        </div>
    @endif

    <div class="lr-field lr-field-full">
        <label for="description">Detailed Description <span style="color:#dc2626;">*</span></label>
        <textarea
            id="description"
            name="description"
            rows="4"
            placeholder="Describe distinguishing marks, color, brand, stickers, or contents (min 10 characters)..."
            required
        >{{ old('description', $report->description ?? '') }}</textarea>
        @error('description')
            <div class="lr-field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="lr-field lr-field-full">
        <label for="photo">Item Photo (Optional)</label>
        <input
            type="file"
            id="photo"
            name="photo"
            accept="image/jpeg,image/png,image/webp"
            onchange="previewLostReportPhoto(event)"
        >
        <div class="lr-field-hint">Accepted formats: JPG, PNG, WEBP (max 4 MB).</div>
        @error('photo')
            <div class="lr-field-error">{{ $message }}</div>
        @enderror

        @if ($isEdit && $report->photo_url)
            <div class="lr-photo-preview" id="existing-photo-box">
                <img src="{{ $report->photo_url }}" alt="Current photo of {{ $report->item_name }}">
            </div>
            <label style="display:inline-flex; align-items:center; gap:0.45rem; margin-top:0.5rem; font-weight:500;">
                <input type="checkbox" name="remove_photo" value="1" @checked(old('remove_photo'))>
                Remove current photo
            </label>
        @endif

        <div class="lr-photo-preview" id="new-photo-preview" style="display:none;">
            <img id="new-photo-img" src="" alt="Selected photo preview">
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewLostReportPhoto(event) {
        const file = event.target.files && event.target.files[0];
        const previewBox = document.getElementById('new-photo-preview');
        const previewImg = document.getElementById('new-photo-img');

        if (!file) {
            previewBox.style.display = 'none';
            previewImg.src = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            previewBox.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush
