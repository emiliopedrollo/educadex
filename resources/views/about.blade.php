@extends('layout')

@section('content')

    <div class="col-sm-10 offset-sm-1 my-auto col-lg-6 offset-lg-3">

        <h2 class="mt-5">BEM VINDO AO EDUCADEX</h2>
        <p>
            <b>O problema</b>: Em muitas ocasiões, é relevante a busca de informações sobre dados na educação no Brasil,
            e em muitos destes casos a complexidade de pesquisa em sites e documentos cheio de dados complexos e
            desorganizados acaba por ser um impeditivo. Visto isso, decidiu-se desenvolver uma plataforma web (site)
            para buscar essas informações de forma rápida e intuitiva.
        </p>
        <p>
            <b>Nossa solução</b>:O Educadex é um sistema web que permite seus usuários a encontrar informações
            referentes a educação Brasileira e sanar suas dúvidas de forma rápida e simples. O sistema conta com uma
            base de dados vasta, que pode ser explorada por qualquer usuário.
        </p>
        <p>
            Caso deseje saber mais sobre o Educadex, clica no botão “Ir para Wiki”. Do contrário, sinta-se livre para
            começar a pesquisar clicando no botão “Continuar”
        </p>
        <a class="btn btn-primary" href="{{ route('home') }}">Continuar</a>
        <a class="btn btn-outline-primary" href="https://github.com/emiliopedrollo/educadex/wiki"
            target="_blank">Ir para Wiki</a>
    </div>

@endsection
