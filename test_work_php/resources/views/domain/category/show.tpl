{extends file='layouts/app.tpl'}

{block name='content'}
    <section class="page category-page">
        <h1>{$view->title|escape}</h1>
        <p class="page__lead">{$view->description|escape}</p>

        <ul class="category-article-list">
            {foreach $view->articles as $article}
                <li class="category-list-card">
                    <a class="category-list-card__media" href="/articles/{$article.slug|escape:'url'}" aria-hidden="true" tabindex="-1">
                        <img src="{if $article.image}{$article.image|escape}{else}/no-image.png{/if}" alt="" loading="lazy">
                    </a>

                    <div class="category-list-card__content">
                        <h2 class="category-list-card__title">
                            <a href="/articles/{$article.slug|escape:'url'}">{$article.title|escape}</a>
                        </h2>

                        {if $article.description}
                            <p class="category-list-card__description">{$article.description|escape}</p>
                        {/if}

                        <p class="category-list-card__meta">
                            <span>{$article.views} просмотров</span>
                            <span>{$article.publishedAt|escape}</span>
                        </p>
                    </div>
                </li>
            {/foreach}
        </ul>

        {include file='components/pagination.tpl' pagination=$view->pagination}
    </section>
{/block}
