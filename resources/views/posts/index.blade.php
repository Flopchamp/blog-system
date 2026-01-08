<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('All Blog Posts') }}
            </h2>
            @auth
                <a href="{{ route('posts.create') }}" 
                   style="background-color: #3b82f6; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                    ✍️ Write New Post
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div style="background-color: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if($posts->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
                    @foreach($posts as $post)
                        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: transform 0.3s;">
                            
                            <!-- Featured Image -->
                            @if($post->featured_image)
                                <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                     alt="{{ $post->title }}"
                                     style="width: 100%; height: 200px; object-fit: cover;">
                            @else
                                <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                                    📝
                                </div>
                            @endif

                            <div style="padding: 20px;">
                                <!-- Category Badge -->
                                @if($post->category)
                                    <span style="background-color: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                        {{ $post->category->name }}
                                    </span>
                                @endif

                                <!-- Read More Button -->
                                    <div style="margin-top: 15px;">
                                        <a href="{{ route('posts.show', $post) }}" 
                                        style="display: block; background-color: #667eea; color: white; padding: 10px; text-align: center; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px;">
                                            📖 Read Full Post
                                        </a>
                                    </div>

                                    <!-- Action Buttons (only for post owner) -->
                                    @auth
                                        @if($post->user_id === auth()->id())
                                            <div style="display: flex; gap: 10px; margin-top: 15px;">
                                                <a href="{{ route('posts.edit', $post) }}" 
                                                style="flex: 1; background-color: #3b82f6; color: white; padding: 8px; text-align: center; border-radius: 6px; text-decoration: none; font-size: 14px;">
                                                    Edit
                                                </a>
                                                <form action="{{ route('posts.destroy', $post) }}" 
                                                    method="POST" 
                                                    style="flex: 1;"
                                                    onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            style="width: 100%; background-color: #ef4444; color: white; padding: 8px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth

                                <!-- Excerpt -->
                                @if($post->excerpt)
                                    <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin-bottom: 15px;">
                                        {{ Str::limit($post->excerpt, 100) }}
                                    </p>
                                @endif

                                <!-- Tags -->
                                @if($post->tags->count() > 0)
                                    <div style="margin: 10px 0;">
                                        @foreach($post->tags as $tag)
                                            <span style="background-color: #f3f4f6; color: #4b5563; padding: 3px 10px; border-radius: 8px; font-size: 11px; margin-right: 5px;">
                                                #{{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Author and Date -->
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e7eb; font-size: 13px; color: #6b7280;">
                                    <span>👤 {{ $post->user->name }}</span>
                                    <span>📅 {{ $post->created_at->format('M d, Y') }}</span>
                                </div>

                                <!-- Status Badge -->
                                <div style="margin-top: 10px;">
                                    @if($post->status === 'published')
                                        <span style="background-color: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                            ✓ Published
                                        </span>
                                    @else
                                        <span style="background-color: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                            📝 Draft
                                        </span>
                                    @endif
                                </div>

                                <!-- Action Buttons (only for post owner) -->
                                @auth
                                    @if($post->user_id === auth()->id())
                                        <div style="display: flex; gap: 10px; margin-top: 15px;">
                                            <a href="{{ route('posts.edit', $post) }}" 
                                               style="flex: 1; background-color: #3b82f6; color: white; padding: 8px; text-align: center; border-radius: 6px; text-decoration: none; font-size: 14px;">
                                                Edit
                                            </a>
                                            <form action="{{ route('posts.destroy', $post) }}" 
                                                  method="POST" 
                                                  style="flex: 1;"
                                                  onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        style="width: 100%; background-color: #ef4444; color: white; padding: 8px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div style="margin-top: 30px;">
                    {{ $posts->links() }}
                </div>

            @else
                <div style="background: white; padding: 60px; text-align: center; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <div style="font-size: 64px; margin-bottom: 20px;">📝</div>
                    <h3 style="font-size: 24px; font-weight: bold; color: #1f2937; margin-bottom: 10px;">No Posts Yet</h3>
                    <p style="color: #6b7280; margin-bottom: 20px;">Be the first to write a blog post!</p>
                    @auth
                        <a href="{{ route('posts.create') }}" 
                           style="background-color: #3b82f6; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block;">
                            ✍️ Write Your First Post
                        </a>
                    @endauth
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
