@extends('layouts.admin')

@section('content')
    <div class="">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <div id="main-admin">
            <div id="search-home-admin">

                <h2>Cerchiamo insieme l'appartamento dei tuoi sogni!</h2>
                <div class="form-group my-form form-inline">
                    <input type="text" class="form-control fluid" placeholder="Everywhere">
                    <button type="button" class="btn btn-success ml-4 mr-2 ">Search</button>
                    <button type="button" class="btn btn-info"><a href="{{route('admin.search')}}">TEST</a> </button>
                </div>
            </div>
        </div>
        <div id="sponsored-apartments">
            <div class="container">
                <h2>Appartamenti Sponsorizzati</h2>
                <div class="row">
                    @php 
                        $i = 1
                    @endphp
                    @foreach ($apartments as $apartment)
                        @if (isset(($apartment->ads)->last()->ad_end) && $today < $apartment->ads->last()->ad_end && $i <= 6)
                            <div class="col-4">
                                <div class="card">
                                    <img class="card-img-top" src="{{asset('storage/' . $apartment->cover_image)}}" alt="Card image cap">
                                    <div class="card-body">
                                        <h3 class="card-title">{{ $apartment->sommary_title }}</h3>
                                        <p class="card-text">{{ $apartment->description }}</p>
                                        <a href="{{ route('admin.apartments.show', ['apartment' => $apartment->id])}}" class="btn btn-primary">Details</a>
                                    </div>
                                </div>
                            </div>
                            @php 
                                $i++
                            @endphp  
                        @endif
                    @endforeach
                     @if ($i == 1)
                        <p id="no-sponsor">Non ci sono appartamenti sponsorizzati al momento</p>
                    @endif
                </div>
            </div>
        </div>

        @include('layouts.partials.footer')
@endsection
