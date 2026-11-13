{{-- Shared by create and edit so field markup + validation display lives in one place. --}}
@php($editing = isset($category))

@if ($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST"
      action="{{ $editing ? route('categories.update', $category) : route('categories.store') }}"
      class="cat-form">
    @csrf
    {{-- HTML forms only speak GET/POST, so this tells Laravel to treat it as PUT --}}
    @if ($editing)
        @method('PUT')
    @endif

    <div class="form-field">
        <label for="name">Name</label>
        {{-- old() wins when validation failed, so the user never retypes what they entered --}}
        <input id="name" name="name" type="text" maxlength="255" required
               value="{{ old('name', $editing ? $category->name : '') }}">
        @error('name')
            <p class="field-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-field">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4">{{ old('description', $editing ? $category->description : '') }}</textarea>
        @error('description')
            <p class="field-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            {{ $editing ? 'Save Changes' : 'Create Category' }}
        </button>
        <a href="{{ route('categories.index') }}" class="btn btn-ghost">Cancel</a>
    </div>
</form>
