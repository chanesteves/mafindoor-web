@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="ui-view">
                <div class="animated fadeIn">
                    <div class="row">
                        <div class="col-sm-6 col-lg-4">
                            <div class="card text-white bg-primary">
                                <div class="card-body pb-0">
                                    <button class="btn btn-transparent p-0 float-right" type="button">
                                        <i class="icon-user"></i>
                                    </button>
                                    <div class="text-value"><h2>{{ number_format($counts['users'], 0) }}</h2></div>
                                    <div>Users</div><br/>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-4">
                            <div class="card text-white bg-warning">
                                <div class="card-body pb-0">
                                    <button class="btn btn-transparent p-0 float-right" type="button">
                                        <i class="icon-location-pin"></i>
                                    </button>
                                    <div class="text-value"><h2>{{ number_format($counts['venues'], 0) }}</h2></div>
                                    <div>Venues</div><br/>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-4">
                            <div class="card text-white bg-danger">
                                <div class="card-body pb-0">
                                    <button class="btn btn-transparent p-0 float-right" type="button">
                                        <i class="icon-magnifier"></i>
                                    </button>
                                    <div class="text-value"><h2>{{ number_format($counts['searches_this_month'], 0) }}</h2></div>
                                    <div>Searches this month</div><br/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  Searches By Venue

                    <div class="card-header-actions">
                        <a href="/venues">View Details &nbsp;&nbsp; <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($searches_by_venue && $searches_by_venue->count() > 0)
                        <div id="searches-by-venue" class="chart no-padding" data-searches="{{ $searches_by_venue }}"></div>
                    @else
                        <center>
                            <strong><h3>No Data</h3></strong>
                        </center>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                  Searches By Platform
                </div>
                <div class="card-body">
                    @if ($searches_by_platform && $searches_by_platform->count() > 0)
                        <div id="searches-by-platform" class="chart no-padding" data-searches="{{ $searches_by_platform }}"></div>
                    @else
                        <center>
                            <strong><h3>No Data</h3></strong>
                        </center>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                  Most Active Users

                    <div class="card-header-actions">
                        <a href="/users">View Details &nbsp;&nbsp; <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($most_active_users && $most_active_users->count() > 0)
                        <table class="table table-bordered most-active">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Activites</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($most_active_users as $most_active_user)
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="user-img col-md-3"><img src="{{ $most_active_user->image }}" width="25" height="25" /></div>
                                                <div class="user-name col-md-8">
                                                    <div class="user-title">{{ $most_active_user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="user-activities">{{ number_format($most_active_user->activities, 0) }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <center>
                            <strong><h3>No Data</h3></strong>
                        </center>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                  Most Searched Places

                    <div class="card-header-actions">
                        <a href="/annotations">View Details &nbsp;&nbsp; <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($most_searched_places && $most_searched_places->count() > 0)
                        <table class="table table-bordered most-searched">
                            <thead>
                                <tr>
                                    <th>Place</th>
                                    <th>Searches</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($most_searched_places as $most_searched_place)
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="place-img col-md-3"><img src="{{ $most_searched_place->image }}" width="50" /></div>
                                                <div class="place-name col-md-8">
                                                    <div class="place-title"><strong>{{ $most_searched_place->place }}</strong></div>
                                                    <div class="place-description"><small>{{ $most_searched_place->floor && $most_searched_place->floor->building ? $most_searched_place->floor->building->name : '' }}, {{ $most_searched_place->floor ? $most_searched_place->floor->name : '' }}</small></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="place-searches"><strong>{{ number_format($most_searched_place->searches, 0) }}</strong></div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <center>
                            <strong><h3>No Data</h3></strong>
                        </center>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  Recent Traffic
                </div>
                <div class="card-body">
                    <div id="recent-traffic" class="chart no-padding" data-traffic="{{ $recent_traffic }}"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
