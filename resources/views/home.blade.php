@extends('layout')

@section('content')
    <div class="d-flex h-100">
        <div class="col-sm-10 offset-sm-1 my-auto col-lg-6 offset-lg-3">
            <div class="mt-5">
                <educadex-search action="{{ route('search') }}"></educadex-search>
            </div>
            <div class="alert" role="alert">
                <h5 class="alert-heading">Dicas de uso</h5>
                <p class="mb-1">
                    Para utilizar a ferramenta, basta fazer uma pergunta no campo de busca.
                </p>
                <p>
                    O melhor jeito de garantir que entenderemos a sua pergunta, é utilizar do português correto,
                    evitando erros gramaticais e abreviações
                </p>
                <p>Se desejar, <a href="{{ route('about') }}">clique aqui</a> para saber mais sobre o projeto.</p>
            </div>
        </div>
    </div>
@endsection
