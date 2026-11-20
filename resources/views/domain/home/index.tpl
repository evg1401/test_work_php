{extends file='layouts/app.tpl'}

{block name='content'}
    <section class="page">
        <h1>{$view->title|escape}</h1>
        <p class="page__lead">{$view->intro|escape}</p>

        <h2>Категории</h2>
        <ul class="card-list">
            {foreach $view->categories as $category}
                <li class="card">
                    <h3><a href="/categories/{$category.id}">{$category.title|escape}</a></h3>
                    <p>{$category.description|escape}</p>
                </li>
            {/foreach}
        </ul>

        <h2>Последние статьи</h2>
        <ul class="card-list">
            {foreach $view->articles as $article}
                <li class="card">
                    <h3><a href="/articles/{$article.id}">{$article.title|escape}</a></h3>
                    <p>{$article.description|escape}</p>
                </li>
            {/foreach}
        </ul>
    </section>
{/block}
