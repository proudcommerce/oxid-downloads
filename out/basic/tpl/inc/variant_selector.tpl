[{if $oView->isMdVariantView()}]
  <div style="display: none">
  [{foreach from=$oView->getVariantList() name=variants item=variant_product}]
    <div id=mdvariant_[{$variant_product->getId()}]>
      [{include file="inc/product.tpl" product=$variant_product size="thinest" altproduct=$product->getId() isfiltering=false class=lastinlist testid="Variant_"|cat:$variant_product->oxarticles__oxid->value}]
    </div>
  [{/foreach}]
    <div id=mdvariant_[{$product->getId()}]>
      [{include file="inc/product.tpl" product=$product size="thinest" altproduct=$product->getId() isfiltering=false class=lastinlist testid="Variant_"|cat:$variant_product->oxarticles__oxid->value}]
    </div>
  </div>

  <div id="md_variant_box"></div>

  [{oxvariantselect value=$product->getMdVariants() separator=" " artid=$product->getId()}]

  [{oxscript add="oxid.mdVariants.mdAttachAll();"}]
  [{oxscript add="oxid.mdVariants.showMdRealVariant();"}]

[{/if}]