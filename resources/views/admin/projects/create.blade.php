@extends('admin.layout')
@section('title', 'مشروع جديد')
@section('eyebrow', 'من الفكرة إلى المعرض')
@section('heading', 'أضف قصة نجاح جديدة.')
@section('description', 'شارك تفاصيل المشروع، تقنياته، وما يجعله مميزًا.')
@section('actions')<a class="btn btn-quiet" href="{{ route('admin.projects.index') }}">العودة للمشاريع <svg class="ui-icon icon-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M20 12H4m7-7-7 7 7 7"/></svg></a>@endsection
@section('content')
<form method="POST" action="{{ route('admin.projects.store') }}" class="editor-form">@csrf @include('admin.projects.form')<div class="form-actions"><button type="submit" class="btn btn-lime">إضافة المشروع <svg class="ui-icon icon-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M20 12H4m7-7-7 7 7 7"/></svg></button><a class="btn btn-quiet" href="{{ route('admin.projects.index') }}">إلغاء</a></div></form>
@endsection
