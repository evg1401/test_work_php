<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$view->title|escape}</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    {include file='components/header.tpl'}

    <main>
        {block name='content'}{/block}
    </main>

    {include file='components/footer.tpl'}
</body>
</html>
