{block name="widgets_emotion_components_ffuenf_emotion_manufacturer"}
    {if $Data.style == 1}
        <ul class="menu--list menu--level-0" style="width: 100%;">
            <li class="menu--list-item item--level-0" style="width: 100%;">
                {if $Data.showHeader}
                    {if $Data.landingpageLink != ''}
                        <a class="menu--list-item-link" href="{$Data.landingpageLink}" title="{$Data.landingpageTitle}" itemprop="url" aria-label="{$Data.landingpageTitle}">{$Data.header}</a>
                    {else}
                        <span class="menu--list-item-link">{$Data.header}</span>
                    {/if}
                {/if}
                <ul class="menu--list menu--level-1 columns--2" style="width: 100%;">
                    {foreach $Data.manufacturers as $sManufacturer}
                        <li class="menu--list-item item--level-1" role="menuitem">
                            <a class="menu--list-item-link" href="{$sManufacturer.url}" title="{$sManufacturer.name}" itemprop="url" aria-label="{$sManufacturer.name}">
                                <span itemprop="name">{$sManufacturer.name}</span>
                            </a>
                        </li>
                    {/foreach}
                    {if $Data.showHeader && $Data.landingpageLink != ''}
                        <li class="menu--list-item item--level-1 showall" role="menuitem">
                            <a class="menu--list-item-link" href="{$Data.landingpageLink}" title="{$Data.landingpageTitle}" itemprop="url" aria-label="{$Data.landingpageTitle}">
                                <span itemprop="name">{$Data.landingpageTitle}</span>
                            </a>
                        </li>
                    {/if}
                </ul>
            </li>
        </ul>
    {/if}
    {if $Data.style == 2}
        <ul class="manufacturer-list">
            {foreach $Data.manufacturers as $sManufacturer}
                {if $sManufacturer.img}
                    <li><a href="{$sManufacturer.url}" title="{$sManufacturer.name}"><img src="{$sManufacturer.img}" alt="{$sManufacturer.name}" /></a></li>
                {/if}
            {/foreach}
        </ul>
    {/if}
{/block}
