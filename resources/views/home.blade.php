@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Your Github Repositories') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


                    @if (isset($repositories))
                        @foreach ($repositories as $name => $repos)
                            <div class="owner">
                                <h3>{{ $name }}</h3>
                                <ul class="repos">
                            @foreach ($repos as $repo)
                                    <li class="repo">
                                        {{ $repo->name }}    
                                    </li>
                            @endforeach
                                </ul>
                            </div>
                        @endforeach
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
