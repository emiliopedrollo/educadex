@extends('layout')

@section('content')
    <div class="col-sm-10 offset-sm-1 my-auto col-lg-6 offset-lg-3">
        <educadex-search action="{{ route('search') }}" question="{{ request('search') }}"></educadex-search>
    </div>
    <hr class="my-4">

    <div class="accordion col-sm-10 offset-sm-1">

        @foreach($answer->getWarnings() as $warning)
            <div class="alert alert-warning" role="alert">{{ $warning }}</div>
        @endforeach

        @switch($answer->getType())
            @case(\App\Tree\Answer::NUMBER)
            @case(\App\Tree\Answer::NAME)
                {{ $answer->getValue() }}
                @break
            @case(\App\Tree\Answer::LIST)
                <table class="table table-sm">
                    <tbody>
                    @foreach($answer->getValue() as $item)
                        <tr>
                            <td>{{ $item }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @break
            @default
            @case(\App\Tree\Answer::UNKNOWN)
                Desculpa, mas não conseguimos entender a sua pergunta
                @break
        @endswitch
    </div>
    <hr>
    <div class="accordion col-sm-10 offset-sm-1">
        <a href="https://google.com">Deixe seu comentário</a>
    </div>
    @if( App::environment('local') ):
    <hr class="my-4">
    <div class="accordion col-sm-10 offset-sm-1">


        <p>
            <a class="btn btn-primary" data-toggle="collapse" href="#analysis" role="button"
               aria-expanded="false" aria-controls="analysis">Toggle analysis</a>
        </p>
        <div class="row">
            <div class="col">
                <div class="collapse multi-collapse" id="analysis">
                    <div class="card card-body">
                        {!! (nl2br(e($analysis))) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
