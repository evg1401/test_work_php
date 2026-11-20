{extends file='layouts/app.tpl'}

{block name='content'}
    <section class="page">
        <h1>{$view->title|escape}</h1>
        <p class="page__lead">{$view->description|escape}</p>

        <ul class="card-list">
            {foreach $view->articles as $article}
                <li class="card">
                    <h2><a href="/articles/{$article.id}">{$article.title|escape}</a></h2>
                    <p>{$article.description|escape}</p>
                </li>
            {/foreach}
        </ul>

        {include file='components/pagination.tpl' pagination=$view->pagination}
    </section>
{/block}
