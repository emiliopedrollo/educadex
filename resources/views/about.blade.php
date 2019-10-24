@extends('layout')

@section('content')

    <div class="col-sm-10 offset-sm-1 my-auto col-lg-6 offset-lg-3">

        <h2>BEM VINDO AO EDUCADEX</h2>
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
            <b>Dicas de uso</b>:
        </p>
        <ul>
            <li>
                Para utilizar a ferramenta, basta fazer uma pergunta no campo de busca, e depois clicar no botão
                para pesquisar
            </li>
            <li>
                O melhor jeito de garantir que entenderemos a sua pergunta, é utilizar do português correto,
                evitando erros gramaticais e abreviações
            </li>
            <li>
                Caso a ferramenta não compreenda a sua pergunta, tente reescrevê-la de outra forma
            </li>
            <li>
                Existe a possibilidade de não possuirmos dados sobre a pergunta feita. Nesta situação a
                ferramenta irá responder que “Não conseguimos entender sua pergunta”
            </li>
            <li>
                Caso deseje saber mais sobre o Educadex, e a abrangência dos dados que podem ser consultados,
                clica no botão “acessar a wiki”. Do contrário, sinta-se livre para começar a pesquisar clicando
                no botão “continuar”
            </li>
        </ul>

        <a class="btn btn-primary" href="{{ route('home') }}" role="button" aria-controls="analysis">Começar</a>
    </div>

@endsection
