{extends file='layouts/app.tpl'}

{block name='content'}
    <div class="category-directory">
        <aside class="category-directory__sidebar">
            <h1 class="category-directory__title">Категории</h1>

            <nav aria-label="Категории">
                <a class="category-directory__all" href="/">Все статьи</a>

                <ul class="category-directory__navigation">
                    {foreach $view->categories as $category}
                        <li>
                            <a href="{$category.url|escape}">
                                <span>{$category.title|escape}</span>
                                <span>{$category.articles|count}</span>
                            </a>
                        </li>
                    {/foreach}
                </ul>
            </nav>
        </aside>

        <div class="category-directory__content">
            {if $view->categories}
                {foreach $view->categories as $category}
                    <section class="category-section" id="category-{$category.slug|escape}">
                        <header class="category-section__header">
                            <h2>{$category.title|escape}</h2>
                            <a href="{$category.url|escape}">Все</a>
                        </header>

                        <div class="category-section__articles">
                            {foreach $category.articles as $article}
                                <article class="category-article-card">
                                    <a class="category-article-card__media{if !$article.image} category-article-card__media--placeholder{/if}" href="/articles/{$article.slug|escape:'url'}" aria-hidden="true" tabindex="-1">
                                        <img src="{if $article.image}{$article.image|escape}{else}/no-image.png{/if}" alt="" loading="lazy">
                                    </a>

                                    <h3 class="category-article-card__title">
                                        <a href="/articles/{$article.slug|escape:'url'}">{$article.title|escape}</a>
                                    </h3>

                                    {if $article.description}
                                        <p class="category-article-card__description">{$article.description|escape}</p>
                                    {/if}

                                    <p class="category-article-card__meta">
                                        {$article.publishedAt|escape} · Просмотров: {$article.views}
                                    </p>
                                </article>
                            {/foreach}
                        </div>
                    </section>
                {/foreach}
            {else}
                <p>Категории со статьями пока отсутствуют.</p>
            {/if}
        </div>
    </div>
{/block}
