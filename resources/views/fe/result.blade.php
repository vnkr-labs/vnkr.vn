@extends('fe.index')
@section('title', ($category->first()->name ?? 'Chuyên Mục') . ' — VNKR')
@section('meta_description', 'Tin tức chuyên mục ' . ($category->first()->name ?? 'VNKR') . ' — Được biên tập bởi Phạm Thế Bảo / VNKR')
@php $__catFirst = $category->first(); @endphp
@section('canonical', $__catFirst && $__catFirst->slug ? route('category.slug', $__catFirst->slug) : route('result', $__catFirst->id ?? 0))

@section('main')
@php
$catName = $category->first()->name ?? 'Chuyên Mục';
$catId   = $category->first()->id ?? null;
// Latest in same cat for sidebar
$sidebarPosts = \App\Models\Product::where('category_id', $catId)
    ->orderBy('created_at','desc')->limit(5)->get();
// All categories for sidebar
$allCats = \App\Models\Category::where('status',1)->get();
@endphp

{{-- Breaking ticker --}}
@php $breakingNews = \App\Models\Product::orderBy('created_at','desc')->limit(5)->get(); @endphp
<div class="breaking-bar">
  <div class="container d-flex align-items-center" style="overflow:hidden;">
    <span class="breaking-label"><i class="bi bi-lightning-fill me-1"></i>NÓNG</span>
    <div class="ticker-wrap">
      <div class="ticker-content">
        @foreach($breakingNews as $b)
          <a href="{{ route('detail', $b->slug) }}">{{ $b->name }}</a>
        @endforeach
      </div>
    </div>
  </div>
</div>

<div class="container" style="padding-top:20px;">

  {{-- Page heading --}}
  <div class="d-flex align-items-center gap-3 mb-4">
    <h1 class="section-head mb-0" style="font-size:22px;">{{ strtoupper($catName) }}</h1>
    <span class="text-muted" style="font-size:13px;">{{ $products->total() }} bài viết</span>
  </div>

  <div class="main-wrapper">
    <div>
      {{-- First article — full width highlight --}}
      @if($products->count() > 0)
      @php $firstResult = $products->first(); @endphp
      <div class="cat-block mb-4 p-0" style="overflow:hidden;">
        <div class="row g-0">
          <div class="col-md-6">
            <a href="{{ route('detail', $firstResult->slug) }}">
              <img src="{{ asset('storage/images/'.$firstResult->image) }}"
                   alt="{{ $firstResult->name }}" loading="eager" decoding="async"
                   style="width:100%;height:260px;object-fit:cover;">
            </a>
          </div>
          <div class="col-md-6 p-3 d-flex flex-column justify-content-center">
            <span class="cat-badge mb-2">{{ $catName }}</span>
            <h2 style="font-size:20px;font-weight:700;line-height:1.4;">
              <a href="{{ route('detail', $firstResult->slug) }}">{{ $firstResult->name }}</a>
            </h2>
            <p class="text-muted" style="font-size:14px;margin:8px 0;">
              {{ Str::limit($firstResult->tomtat, 150) }}
            </p>
            <div style="font-size:12.5px;color:#888;">
              <i class="bi bi-clock me-1"></i>
              {{ \Carbon\Carbon::parse($firstResult->created_at)->format('H:i, d/m/Y') }}
            </div>
          </div>
        </div>
      </div>

      {{-- Rest as grid --}}
      <div class="result-grid">
        @foreach($products->slice(1) as $item)
        <div class="result-card">
          <a href="{{ route('detail', $item->slug) }}">
            <img src="{{ asset('storage/images/'.$item->image) }}" alt="{{ $item->name }}" loading="lazy" decoding="async">
          </a>
          <div class="rc-body">
            <span class="cat-badge mb-1">{{ $catName }}</span>
            <h3><a href="{{ route('detail', $item->slug) }}">{{ $item->name }}</a></h3>
            <div class="rc-meta">
              <i class="bi bi-clock me-1"></i>
              {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
            </div>
            <p class="rc-desc">{{ Str::limit($item->tomtat, 80) }}</p>
          </div>
        </div>
        @endforeach
      </div>
      @endif

      {{-- Pagination --}}
      <div class="mt-4 d-flex justify-content-center">
        {{ $products->links('vendor.pagination.custom-pagination') }}
      </div>
    </div>

    {{-- SIDEBAR --}}
    <aside class="sidebar">
      <div class="sidebar-widget">
        <div class="widget-title"><i class="bi bi-clock-history me-1"></i>Mới Nhất — {{ $catName }}</div>
        @foreach($sidebarPosts as $sp)
        <div class="most-read-item">
          <div class="rank">{{ $loop->iteration }}</div>
          <h5><a href="{{ route('detail', $sp->slug) }}">{{ $sp->name }}</a></h5>
        </div>
        @endforeach
      </div>

      <div class="sidebar-widget">
        <div class="widget-title"><i class="bi bi-grid me-1"></i>Các Chuyên Mục</div>
        <ul class="list-unstyled mb-0">
          @foreach($allCats as $ac)
          <li class="border-bottom py-2">
            <a href="{{ $ac->slug ? route('category.slug', $ac->slug) : route('result', $ac->id) }}"
               style="font-size:13.5px;font-weight:{{ $ac->id == $catId ? '700' : '500' }};
                      color:{{ $ac->id == $catId ? 'var(--brand)' : 'inherit' }};">
              <i class="bi bi-chevron-right me-1 brand-color"></i>{{ $ac->name }}
            </a>
          </li>
          @endforeach
        </ul>
      </div>

      <div class="sidebar-widget">
        <div class="widget-title"><i class="bi bi-graph-up me-1"></i>Thị Trường</div>
        <table class="market-table">
          <tr><th>Loại</th><th>Mua</th><th>Bán</th></tr>
          <tr><td>Vàng SJC</td><td class="up">141,9 tr</td><td class="down">144,9 tr</td></tr>
          <tr><td>USD</td><td class="up">25.130</td><td class="down">25.480</td></tr>
        </table>
      </div>
    </aside>
  </div>
</div>
@endsection
