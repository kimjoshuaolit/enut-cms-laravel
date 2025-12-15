@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12 space-y-6 xl:col-span-12">
            <x-enutrition.enutrition-metrics />
            <x-enutrition.monthly-sale />
        </div>




        <div class="col-span-12 space-y-6">
            {{-- <x-enutrition.statistics-chart /> --}}

            {{-- <x-enutrition.recent-orders /> --}}
        </div>

        {{-- s --}}

        {{-- <div class="col-span-12 xl:col-span-7">
        </div> --}}
    </div>
@endsection
