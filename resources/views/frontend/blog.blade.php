@extends('frontend.layouts.app')

@section('title', 'Our Story - Learn About Our Journey')
@section('meta_description', 'Read our latest posts about organic foods, dry fruits, nutrition tips, and healthy living.')
@section('meta_keywords', 'organic food blog, dry fruits, nutrition, healthy living')

@push('styles')
<style>
.blog-hero {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 80px 0 60px;
    text-align: center;
}

.blog-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 30px;
    height: 100%;
}

.blog-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.blog-image {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-radius: 15px 15px 0 0;
}

.blog-content {
    padding: 25px;
    display: flex;
    flex-direction: column;
    height: calc(100% - 250px);
}

.blog-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 15px;
    line-height: 1.4;
    flex-grow: 1;
}

.blog-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
}

.blog-title a:hover {
    color: var(--primary-color, #d4af37);
}

.blog-excerpt {
    color: #6c757d;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 20px;
    flex-grow: 1;
}

.blog-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #eee;
    font-size: 0.875rem;
    color: #6c757d;
}

.blog-date {
    display: flex;
    align-items: center;
    gap: 5px;
}

.read-more {
    color: var(--primary-color, #d4af37);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.read-more:hover {
    color: #b8941f;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 50px;
}

.pagination {
    display: flex;
    gap: 10px;
    align-items: center;
}

.pagination .page-link {
    padding: 10px 15px;
    border: 1px solid #dee2e6;
    color: #6c757d;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.pagination .page-link:hover,
.pagination .page-item.active .page-link {
    background-color: var(--primary-color, #d4af37);
    border-color: var(--primary-color, #d4af37);
    color: white;
}

.no-blogs {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.no-blogs i {
    font-size: 4rem;
    margin-bottom: 20px;
    color: #dee2e6;
}

@media (max-width: 768px) {
    .blog-hero {
        padding: 60px 0 40px;
    }
    
    .blog-image {
        height: 200px;
    }
    
    .blog-content {
        padding: 20px;
    }
    
    .blog-title {
        font-size: 1.1rem;
    }
}
</style>
@endpush

@section('content')
<!-- Blog Hero Section -->
<section class="blog-hero">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Our Story</h1>
        <p class="lead text-muted">{{ __('Stay updated with nutrition tips, organic food guides, and healthy living ideas') }}</p>
    </div>
</section>

<!-- Blog Posts Section -->
<section class="py-5">
    <div class="container">
        @if($blogs->count() > 0)
            <div class="row">
                @foreach($blogs as $blog)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <article class="blog-card">
                            @if($blog->image)
                                <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" class="blog-image">
                            @else
                                <div class="blog-image d-flex align-items-center justify-content-center bg-light">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            <div class="blog-content">
                                <h3 class="blog-title">
                                    <a href="{{ route('blog.detail', $blog->slug) }}">{{ $blog->title }}</a>
                                </h3>
                                
                                @if($blog->short_description)
                                    <p class="blog-excerpt">{{ Str::limit($blog->short_description, 120) }}</p>
                                @endif
                                
                                <div class="blog-meta">
                                    <span class="blog-date">
                                        <i class="far fa-calendar-alt"></i>
                                        {{ $blog->created_at->format('M d, Y') }}
                                    </span>
                                    <a href="{{ route('blog.detail', $blog->slug) }}" class="read-more">
                                        Read More <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($blogs->hasPages())
                <div class="pagination-wrapper">
                    {{ $blogs->links() }}
                </div>
            @endif
        @else
            <div class="no-blogs">
                <i class="fas fa-blog"></i>
                <h3>No Blog Posts Found</h3>
                <p>We're working on creating amazing content for you. Please check back soon!</p>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
// Add any blog-specific JavaScript here
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for pagination links
    const paginationLinks = document.querySelectorAll('.pagination .page-link');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Add loading state if needed
            this.style.opacity = '0.7';
        });
    });
});
</script>
@endpush