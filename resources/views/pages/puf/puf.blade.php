@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="PUF" />

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-1">
        <div class="space-y-6">
            <x-post-item.post-item-add category="PUF" />

        </div>
        <div class="space-y-6">
            <x-post-item.post-items-table category="PUF" />

        </div>
    </div>

    {{-- Edit Modal Component (Hidden until triggered) --}}
    <x-post-item.post-item-edit category="PUF" />
@endsection
