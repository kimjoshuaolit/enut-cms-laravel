@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Monograph" />

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-1">
        <div class="space-y-6">
            <x-post-item.post-item-add category="Monograph" />

        </div>
        <div class="space-y-6">
            <x-post-item.post-items-table category="Monograph" />

        </div>
    </div>
@endsection
