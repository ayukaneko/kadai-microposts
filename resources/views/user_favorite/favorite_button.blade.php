
    @if (Auth::user()->is_favorite($micropost->id))
        {{-- アンfavoriteボタンのフォーム --}}
        {!! Form::open(['route' => ['favorites.unfavorite', $micropost->id], 'method' => 'delete']) !!}
            {!! Form::submit('Unfavorite', ['class' => "btn btn-danger btn-sm"]) !!}
        {!! Form::close() !!}
    @else
        {{-- favoriteボタンのフォーム --}}
        {!! Form::open(['route' => ['favorites.favorite', $micropost->id], 'method' => 'post']) !!}
            {!! Form::submit('favorite', ['class' => "btn btn-primary btn-sm"]) !!}
        {!! Form::close() !!}
    @endif