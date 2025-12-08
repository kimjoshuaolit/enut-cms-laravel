@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Presentation" />

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-1">
        <div class="space-y-6">
            <x-post-item.post-item-add category="Presentation" />

        </div>
        <div class="space-y-6">
            <x-post-item.post-items-table category="Presentation" />

        </div>
    </div>
@endsection
