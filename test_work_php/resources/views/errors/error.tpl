{extends file='layouts/app.tpl'}

{block name='content'}
    <section>
        <h1>{$view->statusCode|escape}: {$view->title|escape}</h1>
        <p>{$view->message|escape}</p>
    </section>
{/block}
