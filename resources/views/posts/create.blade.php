<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-6">
                            <label for="title" style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">
                                Post Title *
                            </label>
                            <input id="title" 
                                   type="text" 
                                   name="title" 
                                   value="{{ old('title') }}"
                                   style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px;"
                                   placeholder="Enter an engaging title..."
                                   required>
                            @error('title')
                                <p style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-6">
                            <label for="category_id" style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">
                                Category *
                            </label>
                            <select id="category_id" 
                                    name="category_id" 
                                    style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px;"
                                    required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Excerpt -->
                        <div class="mb-6">
                            <label for="excerpt" style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">
                                Excerpt (Short Summary)
                            </label>
                            <textarea id="excerpt" 
                                      name="excerpt" 
                                      rows="3"
                                      style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px;"
                                      placeholder="A brief summary of your post (optional)...">{{ old('excerpt') }}</textarea>
                            @error('excerpt')
                                <p style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                            <p style="font-size: 13px; color: #6b7280; margin-top: 5px;">Max 500 characters</p>
                        </div>

                        <!-- Content -->
                        <div class="mb-6">
                            <label for="content" style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">
                                Post Content *
                            </label>
                            <textarea id="content" 
                                      name="content" 
                                      rows="15"
                                      style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; font-family: inherit;"
                                      placeholder="Write your amazing content here..."
                                      required>{{ old('content') }}</textarea>
                            @error('content')
                                <p style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Featured Image -->
                        <div class="mb-6">
                            <label for="featured_image" style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">
                                Featured Image
                            </label>
                            <input id="featured_image" 
                                   type="file" 
                                   name="featured_image"
                                   accept="image/*"
                                   style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px;">
                            @error('featured_image')
                                <p style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                            <p style="font-size: 13px; color: #6b7280; margin-top: 5px;">Max 2MB (JPG, PNG, GIF)</p>
                        </div>

                        <!-- Tags -->
                        <div class="mb-6">
                            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">
                                Tags
                            </label>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px;">
                                @foreach($tags as $tag)
                                    <label style="display: flex; align-items: center; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                                        <input type="checkbox" 
                                               name="tags[]" 
                                               value="{{ $tag->id }}"
                                               {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                                               style="margin-right: 8px;">
                                        <span>{{ $tag->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('tags')
                                <p style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-6">
                            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">
                                Publish Status *
                            </label>
                            <div style="display: flex; gap: 20px;">
                                <label style="display: flex; align-items: center; cursor: pointer;">
                                    <input type="radio" 
                                           name="status" 
                                           value="draft"
                                           {{ old('status', 'draft') === 'draft' ? 'checked' : '' }}
                                           style="margin-right: 8px;">
                                    <span>📝 Save as Draft</span>
                                </label>
                                <label style="display: flex; align-items: center; cursor: pointer;">
                                    <input type="radio" 
                                           name="status" 
                                           value="published"
                                           {{ old('status') === 'published' ? 'checked' : '' }}
                                           style="margin-right: 8px;">
                                    <span>✓ Publish Now</span>
                                </label>
                            </div>
                            @error('status')
                                <p style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
                            <a href="{{ route('posts.index') }}" 
                               style="background-color: #6b7280; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                                Cancel
                            </a>
                            <button type="submit" 
                                    style="background-color: #3b82f6; color: white; padding: 12px 30px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 16px;">
                                Create Post
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
