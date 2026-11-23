{if $pagination->total > 1}
    <nav class="pagination">
        <ul class="pagination__list">
            {for $page = 1 to $pagination->total}
                <li>
                    {if $page == $pagination->currentPage}
                        <span class="pagination__current">{$page}</span>
                    {else}
                        <a class="pagination__link" href="{$pagination->baseUrl|escape}?page={$page}&amp;limit={$pagination->limit}">{$page}</a>
                    {/if}
                </li>
            {/for}
        </ul>
    </nav>
{/if}
