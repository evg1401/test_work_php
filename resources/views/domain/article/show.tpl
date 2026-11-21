{extends file='layouts/app.tpl'}

{block name='content'}
    <article class="page article">
        <header>
            <h1>{$view->title|escape}</h1>
            <p class="page__lead">{$view->description|escape}</p>
            <p class="article__meta">Просмотров: {$view->views}</p>
        </header>

        <p>{$view->text|escape}</p>

        <footer class="article__categories">
            Категории:
            {foreach $view->categories as $category name='categories'}
                <a href="/categories/{$category.slug|escape:'url'}">{$category.title|escape}</a>{if !$smarty.foreach.categories.last}, {/if}
            {/foreach}
        </footer>
    </article>
{/block}
