@extends('admin.layout')
@section('title', 'تعديل المشروع')
@section('eyebrow', 'تفاصيل تصنع الفرق')
@section('heading', 'طوّر حكاية مشروعك.')
@section('description', 'حدّث تفاصيل المشروع وتحكّم في طريقة ظهوره.')
@section('actions')<a class="btn btn-quiet" href="{{ route('admin.projects.index') }}">العودة للمشاريع <svg class="ui-icon icon-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M20 12H4m7-7-7 7 7 7"/></svg></a>@endsection
@section('content')
<form method="POST" action="{{ route('admin.projects.update', $project) }}" class="editor-form" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.projects.form')<div class="form-actions"><button type="submit" class="btn btn-lime">حفظ التغييرات <svg class="ui-icon icon-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M20 12H4m7-7-7 7 7 7"/></svg></button><a class="btn btn-quiet" href="{{ route('admin.projects.index') }}">إلغاء</a></div></form>
@endsection
