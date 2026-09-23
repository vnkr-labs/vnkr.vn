{{--
  Thông báo cộng đồng — nhúng bằng:
  @include('fe.partials.ad_slot', ['position' => 'sidebar_top'])
  Chỉ dùng cho nội dung cộng đồng phi thương mại.
--}}
@php $communityNotice = \App\Models\AdSlot::getActive($position ?? ''); @endphp
@if($communityNotice && $communityNotice->code)
<div class="community-notice" data-position="{{ $communityNotice->position }}" style="text-align:center;margin:12px 0;">
  {!! $communityNotice->code !!}
</div>
@endif
