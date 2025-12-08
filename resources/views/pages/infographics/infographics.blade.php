@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Infographics" />
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-1">
        <div class="space-y-6">
            <x-gallery.gallery-add category="Infographics" />

        </div>
        <div class="space-y-6">
            <x-gallery.gallery-table category="Infographics" />

        </div>
    </div>
@endsection
