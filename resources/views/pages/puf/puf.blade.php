@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="PUF" />
    <div class="grid grid-cols-1 gap-6">
        <div>
            <x-puf.puf-add :surveys="$surveys" />
        </div>
        <div>
            <x-puf.puf-table :postItems="$postItems" :pufFiles="$pufFiles" :surveys="$surveys" />
        </div>
    </div>

    <x-puf.puf-edit :surveys="$surveys" />
@endsection
