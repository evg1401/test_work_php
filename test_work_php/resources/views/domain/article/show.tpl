{extends file='layouts/app.tpl'}

{block name='content'}
    <div class="article-layout{if !$view->relatedArticles} article-layout--single{/if}">
        <article class="article">
            <header class="article__header">
                {if $view->categories}
                    <nav class="article__categories" aria-label="Категории статьи">
                        {foreach $view->categories as $category}
                            <a class="article__category" href="{$category.url|escape}">
                                {$category.title|escape}
                            </a>
                        {/foreach}
                    </nav>
                {/if}

                <h1>{$view->title|escape}</h1>

                <p class="article__meta">
                    <span>{$view->views} просмотров</span>
                    <span>Опубликовано {$view->publishedAt|escape}</span>
                </p>
            </header>

            <img class="article__image" src="{if $view->image}{$view->image|escape}{else}/no-image.png{/if}" alt="{$view->title|escape}">

            <div class="article__body">
                <p>{$view->text|escape}</p>
            </div>
        </article>

        {if $view->relatedArticles}
            <aside class="related-articles" aria-label="Похожие статьи">
                <h2>Похожие статьи</h2>

                <ul class="related-articles__list">
                    {foreach $view->relatedArticles as $article}
                        <li class="related-article-card">
                            <a class="related-article-card__media" href="{$article.url|escape}" aria-hidden="true" tabindex="-1">
                                <img src="{if $article.image}{$article.image|escape}{else}/no-image.png{/if}" alt="" loading="lazy">
                            </a>

                            <div class="related-article-card__content">
                                <h3 class="related-article-card__title">
                                    <a href="{$article.url|escape}">{$article.title|escape}</a>
                                </h3>

                                <p class="related-article-card__meta">
                                    {$article.views} просмотров · {$article.publishedAt|escape}
                                </p>
                            </div>
                        </li>
                    {/foreach}
                </ul>
            </aside>
        {/if}
    </div>
{/block}
