{extends file='layouts/app.tpl'}

{block name='content'}
    <section>
        <h1>{$view->title|escape}</h1>
        <p>{$view->text|escape}</p>

        {include file='components/pagination.tpl' pagination=$view->pagination}
    </section>
{/block}
