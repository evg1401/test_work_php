{extends file='layouts/app.tpl'}

{block name='content'}
    <article class="page article">
        <header>
            <h1>{$view->title|escape}</h1>

            {if $view->description}
                <p class="page__lead">{$view->description|escape}</p>
            {/if}

            <p class="article__meta">
                Опубликовано: {$view->publishedAt|escape} · Просмотров: {$view->views}
            </p>
        </header>

        {if $view->image}
            <img class="article__image" src="{$view->image|escape}" alt="{$view->title|escape}">
        {/if}

        <p>{$view->text|escape}</p>

        {if $view->categories}
            <footer class="article__categories">
                Категории:
                {foreach $view->categories as $category name='categories'}
                    <a href="{$category.url|escape}">
                        {$category.title|escape}
                    </a>{if !$smarty.foreach.categories.last}, {/if}
                {/foreach}
            </footer>
        {/if}
    </article>
{/block}
