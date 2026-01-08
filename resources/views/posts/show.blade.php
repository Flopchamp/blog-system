<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Blog Post') }}
            </h2>
            <a href="{{ route('posts.index') }}" 
               style="background-color: #6b7280; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                ← Back to All Posts
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Post Card -->
            <article class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                
                <!-- Featured Image -->
                @if($post->featured_image)
                    <div style="width: 100%; height: 400px; overflow: hidden;">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" 
                             alt="{{ $post->title }}"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                @else
                    <div style="width: 100%; height: 400px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 120px;">
                        📝
                    </div>
                @endif

                <div class="p-8">
                    
                    <!-- Category and Status -->
                    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                        @if($post->category)
                            <span style="background-color: #dbeafe; color: #1e40af; padding: 6px 16px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                                {{ $post->category->name }}
                            </span>
                        @endif
                        
                        @if($post->status === 'published')
                            <span style="background-color: #d1fae5; color: #065f46; padding: 6px 16px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                                ✓ Published
                            </span>
                        @else
                            <span style="background-color: #fef3c7; color: #92400e; padding: 6px 16px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                                📝 Draft
                            </span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h1 style="font-size: 36px; font-weight: bold; color: #1f2937; margin-bottom: 20px; line-height: 1.2;">
                        {{ $post->title }}
                    </h1>

                    <!-- Excerpt -->
                    @if($post->excerpt)
                        <p style="font-size: 18px; color: #6b7280; margin-bottom: 30px; font-style: italic; padding: 20px; background-color: #f9fafb; border-left: 4px solid #667eea; border-radius: 4px;">
                            {{ $post->excerpt }}
                        </p>
                    @endif

                    <!-- Author and Date -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 20px; margin-bottom: 30px; border-bottom: 2px solid #e5e7eb;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: bold;">
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p style="font-weight: 600; color: #1f2937; margin: 0;">{{ $post->user->name }}</p>
                                <p style="font-size: 14px; color: #6b7280; margin: 0;">
                                    Published on {{ $post->published_at ? $post->published_at->format('F d, Y') : $post->created_at->format('F d, Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Tags -->
                        @if($post->tags->count() > 0)
                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                @foreach($post->tags as $tag)
                                    <span style="background-color: #f3f4f6; color: #4b5563; padding: 6px 14px; border-radius: 16px; font-size: 13px; font-weight: 500;">
                                        #{{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div style="font-size: 18px; line-height: 1.8; color: #374151;">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    <!-- Action Buttons (only for post owner) -->
                    @auth
                        @if($post->user_id === auth()->id())
                            <div style="display: flex; gap: 15px; margin-top: 40px; padding-top: 30px; border-top: 2px solid #e5e7eb;">
                                <a href="{{ route('posts.edit', $post) }}" 
                                   style="flex: 1; background-color: #3b82f6; color: white; padding: 14px; text-align: center; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 16px;">
                                    ✏️ Edit Post
                                </a>
                                <form action="{{ route('posts.destroy', $post) }}" 
                                      method="POST" 
                                      style="flex: 1;"
                                      onsubmit="return confirm('Are you sure you want to delete this post? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            style="width: 100%; background-color: #ef4444; color: white; padding: 14px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 16px;">
                                        🗑️ Delete Post
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth

                </div>
            </article>

            <!-- Related Information -->
            <div style="margin-top: 30px; padding: 20px; background-color: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb;">
                <h3 style="font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 15px;">
                    📊 Post Information
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div>
                        <p style="font-size: 13px; color: #6b7280; margin: 0;">Created</p>
                        <p style="font-size: 15px; font-weight: 600; color: #374151; margin: 5px 0 0 0;">
                            {{ $post->created_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                    <div>
                        <p style="font-size: 13px; color: #6b7280; margin: 0;">Last Updated</p>
                        <p style="font-size: 15px; font-weight: 600; color: #374151; margin: 5px 0 0 0;">
                            {{ $post->updated_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                    <div>
                        <p style="font-size: 13px; color: #6b7280; margin: 0;">Reading Time</p>
                        <p style="font-size: 15px; font-weight: 600; color: #374151; margin: 5px 0 0 0;">
                            {{ ceil(str_word_count($post->content) / 200) }} min read
                        </p>
                    </div>
                    <div>
                        <p style="font-size: 13px; color: #6b7280; margin: 0;">Word Count</p>
                        <p style="font-size: 15px; font-weight: 600; color: #374151; margin: 5px 0 0 0;">
                            {{ str_word_count($post->content) }} words
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>