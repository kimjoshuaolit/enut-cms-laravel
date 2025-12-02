@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12 space-y-6 xl:col-span-7">
            <x-enutrition.enutrition-metrics />
            <x-enutrition.monthly-sale />
        </div>
        <div class="col-span-12 xl:col-span-5">
            <x-enutrition.monthly-target />
        </div>

        <div class="col-span-12">
            <x-enutrition.statistics-chart />
        </div>

        <div class="col-span-12 xl:col-span-5">
            <x-enutrition.customer-demographic />
        </div>

        <div class="col-span-12 xl:col-span-7">
            <x-enutrition.recent-orders />
        </div>
    </div>
@endsection
