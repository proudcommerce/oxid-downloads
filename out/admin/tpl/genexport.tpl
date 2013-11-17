[{if $linenr == 0 }]
[{* Add header information here *}]
[{/if}]
'[{$article->oxarticles__oxartnum->value}]';'[{$article->oxarticles__oxtitle->value|strip_tags}]';'[{$article->oxcategories__oxtitle->value|strip_tags}]';'[{$article->oxarticles__oxshortdesc->value|strip_tags}]';'[{$article->oxarticles__oxlongdesc->value|strip_tags}]';[{$article->pic1}];[{$article->oxarticles__oxtprice->value}];[{$article->brutPrice}];[{$article->valid}];[{$article->getLink()|replace:"&amp;":"&"}]