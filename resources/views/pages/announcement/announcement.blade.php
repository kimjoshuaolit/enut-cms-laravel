@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Announcement" />
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-1">
        <div class="space-y-6">
            <x-announcement.announcement-add />
        </div>
        <div class="space-y-6">
            <x-announcement.announcement-table :announcements="$announcements" />
        </div>
    </div>

    <x-announcement.announcement-edit />
@endsection
