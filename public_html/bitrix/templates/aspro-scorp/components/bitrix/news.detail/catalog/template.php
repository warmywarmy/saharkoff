<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?
global $arTheme;
$bOrderViewBasket = $arParams['ORDER_VIEW'];
$basketURL = (isset($arTheme['URL_BASKET_SECTION']) && strlen(trim($arTheme['URL_BASKET_SECTION']['VALUE'])) ? $arTheme['URL_BASKET_SECTION']['VALUE'] : SITE_DIR.'cart/');
$dataItem = ($bOrderViewBasket ? CScorp::getDataItem($arResult) : false);
?>
<div class="item" data-id="<?=$arResult['ID']?>"<?=($bOrderViewBasket ? ' data-item="'.$dataItem.'"' : '')?>>
  <?// element name?>
  <?if($arParams['DISPLAY_NAME'] != 'N' && strlen($arResult['NAME'])):?>
    <h2 class="underline" itemprop="name"><?=$arResult['NAME']?></h2>
  <?endif;?>
  
  <div class="head<?=($arResult['GALLERY'] ? '' : ' wti')?>">
    <div class="row">
      
	  <?if($arResult['GALLERY']):?>
        <div class="col-md-6 col-sm-6">
          <div class="row galery">
            <div class="inner">
              <div class="flexslider unstyled row" id="slider" data-plugin-options='{"animation": "slide", "directionNav": true, "controlNav" :false, "animationLoop": true, "sync": ".detail .galery #carousel", "slideshow": false, "counts": [1, 1, 1]}'>
                <ul class="slides items">
                  <?$countAll = count($arResult['GALLERY']);?>
                  <?foreach($arResult['GALLERY'] as $i => $arPhoto):?>
                    <li class="col-md-1 col-sm-1 item">
                      <a href="<?=$arPhoto['DETAIL']['SRC']?>" class="fancybox blink" rel="gallery" target="_blank" title="<?=$arPhoto['TITLE']?>">
                        <img src="<?=$arPhoto['PREVIEW']['src']?>" class="img-responsive inline" title="<?=$arPhoto['TITLE']?>" alt="<?=$arPhoto['ALT']?>" itemprop="image" />
                        <span class="zoom">
                          <i class="fa fa-16 fa-white-shadowed fa-search-plus"></i>
                        </span>
                      </a>
                    </li>
                  <?endforeach;?>
                </ul>
              </div>
              <?if(count($arResult["GALLERY"]) > 1):?>
                <div class="thmb flexslider unstyled" id="carousel">
                  <ul class="slides">
                    <?foreach($arResult["GALLERY"] as $arPhoto):?>
                      <li class="blink">
                        <img class="img-responsive inline" border="0" src="<?=$arPhoto["THUMB"]["src"]?>" title="<?=$arPhoto['TITLE']?>" alt="<?=$arPhoto['ALT']?>" />
                      </li>
                    <?endforeach;?>
                  </ul>
                </div>
                <style type="text/css">
                .catalog.detail .galery #carousel.flexslider{max-width:<?=ceil(((count($arResult['GALLERY']) <= 3 ? count($arResult['GALLERY']) : 3) * 84.5) - 7.5 + 60)?>px;}
                @media (max-width: 991px){
                  .catalog.detail .galery #carousel.flexslider{max-width:<?=ceil(((count($arResult['GALLERY']) <= 2 ? count($arResult['GALLERY']) : 2) * 84.5) - 7.5 + 60)?>px;}
                }
                </style>
              <?endif;?>
            </div>
            <script type="text/javascript">
            $(document).ready(function(){
              InitFlexSlider(); // for ajax mode
              $('.detail .galery .item').sliceHeight({slice: <?=$countAll?>, lineheight: -3});
              $('.detail .galery #carousel').flexslider({
                animation: 'slide',
                controlNav: false,
                animationLoop: true,
                slideshow: false,
                itemWidth: 77,
                itemMargin: 7.5,
                minItems: 2,
                maxItems: 3,
                asNavFor: '.detail .galery #slider'
              });
            });
            </script>
          </div>
        </div>
      <?endif;?>

      <div class="<?=($arResult['GALLERY'] ? 'col-md-6 col-sm-6' : 'col-md-12 col-sm-12');?>">
        <div class="info" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
          <?
          $frame = $this->createFrame('info')->begin('');
          $frame->setAnimation(true);
          ?>
          <?if($arResult['DISPLAY_PROPERTIES']['STATUS']['VALUE_XML_ID'] || strlen($arResult['DISPLAY_PROPERTIES']['ARTICLE']['VALUE'])):?>
            <div class="hh">
              <?if(strlen($arResult['DISPLAY_PROPERTIES']['STATUS']['VALUE'])):?>
                <span class="label label-<?=$arResult['DISPLAY_PROPERTIES']['STATUS']['VALUE_XML_ID']?>" itemprop="availability" href="http://schema.org/InStock"><?=$arResult['DISPLAY_PROPERTIES']['STATUS']['VALUE']?></span>
              <?endif;?>

			  <?if($minPrice = array_pop($arResult['DISPLAY_PROPERTIES']['PARAM_PRICE']['VALUE']))://vm?>
				<?echo "От ".$minPrice." руб.";?>
			  <?endif;?>
			  
              <?if(strlen($arResult['DISPLAY_PROPERTIES']['ARTICLE']['VALUE'])):?>
                <span class="article">
                  <?=GetMessage('ARTICLE')?>&nbsp; <span><?=$arResult['DISPLAY_PROPERTIES']['ARTICLE']['VALUE']?></span>
                </span>
              <?endif;?>
              <hr/>
            </div>
          <?endif;?>

          <?if(strlen($arResult['FIELDS']['PREVIEW_TEXT'])):?>
            <div class="previewtext" itemprop="description">
              <?// element detail text?>
              <?if($arResult['DETAIL_TEXT_TYPE'] == 'text'):?>
                <p><?=$arResult['FIELDS']['PREVIEW_TEXT'];?></p>
              <?else:?>
                <?=$arResult['FIELDS']['PREVIEW_TEXT'];?>
              <?endif;?>
            </div>
          <?endif;?>

<?if(false&& $USER->IsAdmin()):?>
<?endif;?>

<?//PRICE?>
		<div class="price">
          <?if($arResult['DISPLAY_PROPERTIES']['PARAM_PRICE']['VALUE']):?>
			  <div class="price_new"><span class="price_val" id="mydiv">
				<? echo $arResult['DISPLAY_PROPERTIES']['PARAM_PRICE_DESC']['VALUE'].": <span id='price-div'></span>";?>
			  </span></div>
          <?elseif($arResult['DISPLAY_PROPERTIES']['PRICE']['VALUE']):?>
			  <div class="price_new"><span class="price_val" id="mydiv">
				<? echo "Стоимость, руб: ".$arResult['DISPLAY_PROPERTIES']['PRICE']['VALUE'];?>
			  </span></div>
          <?endif;?>
              
		  <?if(strlen($arResult['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE'])):?>
			<div class="price_old"><?=GetMessage('DISCOUNT_PRICE')?>&nbsp;<span class="price_val"><?=$arResult['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE']?></span></div>
		  <?endif;?>
		</div>

          <?//noknok parameter select?>
          <div style="clearfix"></div>

		<?$shag = $arResult['DISPLAY_PROPERTIES']['PARAM_SHAG']['VALUE'];?>
           <br>

          <?
          if(is_array($arResult['DISPLAY_PROPERTIES']['PARAM_LIST']['VALUE']) && is_array($arResult['DISPLAY_PROPERTIES']['PARAM_PRICE']['VALUE'])
            && count($arResult['DISPLAY_PROPERTIES']['PARAM_LIST']['VALUE']) == count($arResult['DISPLAY_PROPERTIES']['PARAM_PRICE']['VALUE'])):
          ?>
          <!--<select name="city" id="myselect">
            <?for ($item = 0; $item<count($arResult['DISPLAY_PROPERTIES']['PARAM_LIST']['VALUE']); $item++):?>
            <option value="<?echo $arResult['DISPLAY_PROPERTIES']['PARAM_PRICE_DESC']['VALUE'].": ".$arResult['DISPLAY_PROPERTIES']['PARAM_PRICE']['VALUE'][$item]?>"><?=$arResult['DISPLAY_PROPERTIES']['PARAM_LIST']['VALUE'][$item]?></option>
            <?endfor;?>
          </select>-->
          <?endif;?>
          <div style="clearfix"></div>
          <br>
          <?//этот скрипт меняет цену в зависимости от выбранного параметра?>
          <script type="text/javascript">
            document.getElementById("myselect").addEventListener("change", function(){
              document.getElementById('mydiv').innerHTML = this.value;   
            });
          </script>


<style>
.error-mes-block {
	position: absolute;
	width: 100%;
	display: none;
	top: -100%;
	transform: translate(0, -35%);
	left: 0;
	background-color: #fff;
	padding: 5px;
	color: red;
	font-size: 12px;
	border: 1px solid red;}
.pointer-events-none {pointer-events: none;}
</style>



          <?// element buy block?>
          <?if(/*$bOrderViewBasket &&*/ $arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES'):?>
            <div class="buy_block lg clearfix">
          <?if($arResult['DISPLAY_PROPERTIES']['PARAM_NAME']['VALUE']):?>
          <div><h5><?echo $arResult['DISPLAY_PROPERTIES']['PARAM_NAME']['VALUE'];?></h5></div>
          <?endif;?>

              <div class="counter pull-left">
                <div class="wrap">
                  <div class="error-mes-block"><span>Число должно быть больше: <? echo $shag ?></span></div>
                  <span class="minus ctrl bgtransition"></span>
                  <input type="text" value="1" class="count" />
                  <span class="plus ctrl bgtransition"></span>
                </div>
              </div>
              <div class="buttons pull-right">

              <?if($arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES' && !$bOrderViewBasket):?>
                <span class="btn btn-default" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_scorp_form']['aspro_scorp_order_product'][0]?>" data-name="order_product" data-autoload-count="" data-autoload-summa="" data-product="<?=$arResult['NAME']?>"><?=(strlen($arParams['S_ORDER_PRODUCT']) ? $arParams['S_ORDER_PRODUCT'] : GetMessage('S_ORDER_PRODUCT'))?></span>
              <?endif;?>
                <!--<span class="btn btn-default pull-right to_cart" data-quantity="1"><span><?=GetMessage('BUTTON_TO_CART')?></span></span>
                <a href="<?=$basketURL?>" class="btn btn-default pull-right in_cart"><span><?=GetMessage('BUTTON_IN_CART')?></span></a>-->
              </div>
            </div>
          <?endif;?>
          <?if(true|| $arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES' || $arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES'):?>
            <div class="order<?=($bOrderViewBasket ? ' basketTrue' : '')?>">
              <?if($arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES' && !$bOrderViewBasket):?>
                <!--<span class="btn btn-default" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_scorp_form']['aspro_scorp_order_product'][0]?>" data-name="order_product" data-product="<?=$arResult['NAME']?>"><?=(strlen($arParams['S_ORDER_PRODUCT']) ? $arParams['S_ORDER_PRODUCT'] : GetMessage('S_ORDER_PRODUCT'))?></span>-->
              <?endif;?>
              <?if(true|| $arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES'):?>
                <span class="btn btn-default" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_scorp_form']['aspro_scorp_question'][0]?>" data-name="question" data-autoload-NEED_PRODUCT="<?=$arResult['NAME']?>"><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : GetMessage('S_ASK_QUESTION'))?></span>
              <?endif;?>
              <?/*<div class="text"><?=GetMessage('MORE_TEXT')?></div>*/?>
            </div>
          <?endif;?>
          <?/* if($arParams['USE_SHARE'] == 'Y'):?>
            <div class="share">
              <hr />
              <span class="text"><?=GetMessage('SHARE_TEXT')?></span>
              <div class="ya-share2" data-services="vkontakte,facebook,twitter,viber,whatsapp,odnoklassniki,moimir"></div>
            </div>
          <?endif; */?>
          <br>
          <a href="/dostavka-i-oplata/#maket" target="_blank" class="underlinedotted" title="Откроется в новой вкладке">Требования к макетам логотипов</a>
          <br>
          <br>
          <a class="btn" href="/price/Saharkoff_horeca_price.xls" title="Прайс-лист по продукции HORECA в формате XLS"><b>Скачать прайс-лист в XLS</b></a>
          <?$frame->end();?>
        </div>
      </div>
    </div>
  </div>
  <?if(strlen($arResult['FIELDS']['DETAIL_TEXT'])):?>
    <div class="content" itemprop="description">
      <?// element detail text?>
      <?if($arResult['DETAIL_TEXT_TYPE'] == 'text'):?>
        <p><?=$arResult['FIELDS']['DETAIL_TEXT'];?></p>
      <?else:?>
        <?=$arResult['FIELDS']['DETAIL_TEXT'];?>
      <?endif;?>
    </div>
  <?endif;?>

  <?
  $frame = $this->createFrame('order')->begin('');
  $frame->setAnimation(true);
  ?>
  <?// order?>
  <?if($arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES' && !$bOrderViewBasket):?>
    <div class="order-block">
      <div class="row">
        <div class="col-md-4 col-sm-4 col-xs-5 valign">
          <span class="btn btn-default btn-lg" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_scorp_form']['aspro_scorp_order_product'][0]?>" data-name="order_product" data-autoload-count="" data-autoload-summa="" data-product="<?=$arResult['NAME']?>"><?=(strlen($arParams['S_ORDER_PRODUCT']) ? $arParams['S_ORDER_PRODUCT'] : GetMessage('S_ORDER_PRODUCT'))?></span>
        </div>
        <div class="col-md-8 col-sm-8 col-xs-7 valign">
          <div class="text">
            <?$APPLICATION->IncludeComponent(
              'bitrix:main.include',
              '',
              Array(
                'AREA_FILE_SHOW' => 'file',
                'PATH' => SITE_DIR.'include/ask_product.php',
                'EDIT_TEMPLATE' => ''
              )
            );?>
          </div>
        </div>
      </div>
    </div>
  <?endif;?>
  <?$frame->end();?>

  <?// characteristics
// чтобы характеристики отражались нужно убрать восклицательный знак у !$arResult?>
  <?if($arResult['CHARACTERISTICS'])://vm?>
    <div class="wraps">
      <hr />
      <h4 class="underline"><?=(strlen($arParams['T_CHARACTERISTICS']) ? $arParams['T_CHARACTERISTICS'] : GetMessage('T_CHARACTERISTICS'))?></h4>
      <div class="row chars">
        <div class="col-md-12">
          <div class="char-wrapp">
            <table class="props_table">
              <?foreach($arResult['CHARACTERISTICS'] as $arProp):?>
                <tr class="char">
                  <td class="char_name">
                    <?if($arProp['HINT']):?>
                      <div class="hint">
                        <span class="icons" data-toggle="tooltip" data-placement="top" title="<?=$arProp['HINT']?>"></span>
                      </div>
                    <?endif;?>
                    <span><?=$arProp['NAME']?></span>
                  </td>
                  <td class="char_value">
                    <span>
                      <?if(is_array($arProp['DISPLAY_VALUE'])):?>
                        <?foreach($arProp['DISPLAY_VALUE'] as $key => $value):?>
                          <?if($arProp['DISPLAY_VALUE'][$key + 1]):?>
                            <?=$value.'&nbsp;/ '?>
                          <?else:?>
                            <?=$value?>
                          <?endif;?>
                        <?endforeach;?>
                      <?else:?>
                        <?=$arProp['DISPLAY_VALUE']?>
                      <?endif;?>
                    </span>
                  </td>
                </tr>
              <?endforeach;?>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?endif;?>

  <?// docs files?>
  <?if($arResult['DISPLAY_PROPERTIES']['DOCUMENTS']['VALUE']):?>
    <div class="wraps">
      <hr />
      <h4 class="underline"><?=(strlen($arParams['T_DOCS']) ? $arParams['T_DOCS'] : GetMessage('T_DOCS'))?></h4>
      <div class="row docs">
        <?foreach($arResult['PROPERTIES']['DOCUMENTS']['VALUE'] as $docID):?>
          <?$arItem = CScorp::get_file_info($docID);?>
          <div class="col-md-6 <?=$arItem['TYPE']?>">
            <?
            $fileName = substr($arItem['ORIGINAL_NAME'], 0, strrpos($arItem['ORIGINAL_NAME'], '.'));
            $fileTitle = (strlen($arItem['DESCRIPTION']) ? $arItem['DESCRIPTION'] : $fileName);
            ?>
            <a href="<?=$arItem['SRC']?>" target="_blank" title="<?=$fileTitle?>"><?=$fileTitle?></a>
            <?=GetMessage('CT_NAME_SIZE')?>:
            <?=CScorp::filesize_format($arItem['FILE_SIZE']);?>
          </div>
        <?endforeach;?>
      </div>
    </div>
  <?endif;?>

  <?
  $frame = $this->createFrame('video')->begin('');
  $frame->setAnimation(true);
  ?>
  <?// video?>
  <?if($arResult['VIDEO']):?>
    <div class="wraps">
      <hr />
      <h4 class="underline"><?=(strlen($arParams['T_VIDEO']) ? $arParams['T_VIDEO'] : GetMessage('T_VIDEO'))?></h4>
      <div class="row video">
        <?foreach($arResult['VIDEO'] as $i => $arVideo):?>
          <div class="col-md-6 item">
            <div class="video_body">
              <video id="js-video_<?=$i?>" width="350" height="217"  class="video-js" controls="controls" preload="metadata" data-setup="{}">
                <source src="<?=$arVideo["path"]?>" type='video/mp4' />
                <p class="vjs-no-js">
                  To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5 video
                </p>
              </video>
            </div>
            <div class="title"><?=(strlen($arVideo["title"]) ? $arVideo["title"] : $i)?></div>
          </div>
        <?endforeach;?>
      </div>
    </div>
  <?endif;?>
  <?$frame->end();?>
  <script type="text/javascript">
  $(document).ready(function(){
    setBasketItemsClasses();
  });
  </script>

  <script>


$(document).ready(function(){
shag_start = '<?=$shag;?>';
arr_price_start = <?=CUtil::PhpToJSObject($arResult['DISPLAY_PROPERTIES']['PARAM_PRICE']['VALUE'], false)?>;
result_coef_start = parseFloat(arr_price_start[0]);
result_start = (shag_start * result_coef_start).toFixed(0);

$("#price-div").html(result_start);
$(".counter.pull-left input.count").val(shag_start);
$(".buttons.pull-right .btn.btn-default").attr("data-autoload-summa", result_start);
$(".buttons.pull-right .btn.btn-default").attr("data-autoload-count", shag_start);
$("span.btn.btn-default.btn-lg").attr("data-autoload-summa", result_start);
$("span.btn.btn-default.btn-lg").attr("data-autoload-count", shag_start);


$(".count").change(function(){ 
arr_ves = <?=CUtil::PhpToJSObject($arResult['DISPLAY_PROPERTIES']['PARAM_LIST']['VALUE'], false)?>; 
shag = '<?=$shag;?>';

arr_price = <?=CUtil::PhpToJSObject($arResult['DISPLAY_PROPERTIES']['PARAM_PRICE']['VALUE'], false)?>; 

number = $("input.count").val();
  //number = parseFloat(numbernew);
console.log(number);

  if (number >= parseFloat(shag)) {

for (i = 0; i <= arr_ves.length; i++) { 


if (i == arr_ves.length-1) {

console.log(arr_price);


result = parseFloat(arr_ves[i]);
  console.log("Кол-во: " + result);
result_coef = parseFloat(arr_price[i]);
  console.log("Коэфициент: " + result_coef);
result_sum = (number * result_coef).toFixed(0);

$(".buttons.pull-right .btn.btn-default").attr("data-autoload-summa", result_sum);
$(".buttons.pull-right .btn.btn-default").attr("data-autoload-count", number);
$("span.btn.btn-default.btn-lg").attr("data-autoload-summa", result_sum);
$("span.btn.btn-default.btn-lg").attr("data-autoload-count", number);
//if (result_sum < 1) { result_sum = 1 }; console.log("result_sum = 1"); //vm
$("#price-div").html(result_sum);


break;
} else if (number >= parseFloat(arr_ves[i]) && parseFloat(arr_ves[i+1]) > number) {
console.log(arr_price);
result = parseFloat(arr_ves[i]);
  console.log("Кол-во: " + result);
result_coef = parseFloat(arr_price[i]);
  console.log("Коэфициент: " + result_coef);
result_sum = (number * result_coef).toFixed(0);

$(".buttons.pull-right .btn.btn-default").attr("data-autoload-summa", result_sum);
$(".buttons.pull-right .btn.btn-default").attr("data-autoload-count", number);
$("span.btn.btn-default.btn-lg").attr("data-autoload-summa", result_sum);
$("span.btn.btn-default.btn-lg").attr("data-autoload-count", number);
//if (result_sum < 1) { result_sum = 1 }; console.log("result_sum = 1"); //vm
$("#price-div").html(result_sum);

break;
};

};

$(".buttons.pull-right .btn.btn-default").removeClass("pointer-events-none");
$(".valign span.btn.btn-default.btn-lg").removeClass("pointer-events-none");

  } else {
    $(".error-mes-block").fadeIn(500);
    setTimeout(function(){
  $(".error-mes-block").fadeOut(400);
}, 4000);


$(".buttons.pull-right .btn.btn-default").addClass("pointer-events-none");
$(".valign span.btn.btn-default.btn-lg").addClass("pointer-events-none");

};





});



$(".counter .plus").click(function(){ 
arr_ves = <?=CUtil::PhpToJSObject($arResult['DISPLAY_PROPERTIES']['PARAM_LIST']['VALUE'], false)?>; 
shag = '<?=$shag;?>';

arr_price = <?=CUtil::PhpToJSObject($arResult['DISPLAY_PROPERTIES']['PARAM_PRICE']['VALUE'], false)?>; 

number = parseFloat($("input.count").val())+1;

console.log(number);

  if (number >= parseFloat(shag)) {

for (i = 0; i <= arr_ves.length; i++) { 


if (i == arr_ves.length-1) {

console.log(arr_price);


result = parseFloat(arr_ves[i]);
  console.log("Кол-во: " + result);
result_coef = parseFloat(arr_price[i]);
  console.log("Коэфициент: " + result_coef);
result_sum = (number * result_coef).toFixed(0);

$(".buttons.pull-right .btn.btn-default").attr("data-autoload-summa", result_sum);
$(".buttons.pull-right .btn.btn-default").attr("data-autoload-count", number);
$("span.btn.btn-default.btn-lg").attr("data-autoload-summa", result_sum);
$("span.btn.btn-default.btn-lg").attr("data-autoload-count", number);
$("#price-div").html(result_sum);

break;
} else if (number >= parseFloat(arr_ves[i]) && parseFloat(arr_ves[i+1]) > number) {
console.log(arr_price);
result = parseFloat(arr_ves[i]);
  console.log("Кол-во: " + result);
result_coef = parseFloat(arr_price[i]);
  console.log("Коэфициент: " + result_coef);
result_sum = (number * result_coef).toFixed(0);

$(".buttons.pull-right .btn.btn-default").attr("data-autoload-summa", result_sum);
$(".buttons.pull-right .btn.btn-default").attr("data-autoload-count", number);
$("span.btn.btn-default.btn-lg").attr("data-autoload-summa", result_sum);
$("span.btn.btn-default.btn-lg").attr("data-autoload-count", number);
$("#price-div").html(result_sum);

break;
};

};


$(".buttons.pull-right .btn.btn-default").removeClass("pointer-events-none");
$(".valign span.btn.btn-default.btn-lg").removeClass("pointer-events-none");
  } else {
    $(".error-mes-block").fadeIn(500);
    setTimeout(function(){
  $(".error-mes-block").fadeOut(400);
}, 4000);


$(".buttons.pull-right .btn.btn-default").addClass("pointer-events-none");
$(".valign span.btn.btn-default.btn-lg").addClass("pointer-events-none");

};

});
$(".counter .minus").click(function(){ 
arr_ves = <?=CUtil::PhpToJSObject($arResult['DISPLAY_PROPERTIES']['PARAM_LIST']['VALUE'], false)?>; 
shag = '<?=$shag;?>';

arr_price = <?=CUtil::PhpToJSObject($arResult['DISPLAY_PROPERTIES']['PARAM_PRICE']['VALUE'], false)?>; 

number = parseFloat($("input.count").val())-1;

console.log(number);

  if (number >= parseFloat(shag)) {

for (i = 0; i <= arr_ves.length; i++) { 


if (i == arr_ves.length-1) {

console.log(arr_price);


result = parseFloat(arr_ves[i]);
  console.log("Кол-во: " + result);
result_coef = parseFloat(arr_price[i]);
  console.log("Коэфициент: " + result_coef);
result_sum = (number * result_coef).toFixed(0);

$(".buttons.pull-right .btn.btn-default").attr("data-autoload-summa", result_sum);
$(".buttons.pull-right .btn.btn-default").attr("data-autoload-count", number);
$("span.btn.btn-default.btn-lg").attr("data-autoload-summa", result_sum);
$("span.btn.btn-default.btn-lg").attr("data-autoload-count", number);
$("#price-div").html(result_sum);

break;

} else if (number >= parseFloat(arr_ves[i]) && parseFloat(arr_ves[i+1]) > number) {
console.log(arr_price);
result = parseFloat(arr_ves[i]);
  console.log("Кол-во: " + result);
result_coef = parseFloat(arr_price[i]);
  console.log("Коэфициент: " + result_coef);
result_sum = (number * result_coef).toFixed(0);

$(".buttons.pull-right .btn.btn-default").attr("data-autoload-summa", result_sum);
$(".buttons.pull-right .btn.btn-default").attr("data-autoload-count", number);
$("span.btn.btn-default.btn-lg").attr("data-autoload-summa", result_sum);
$("span.btn.btn-default.btn-lg").attr("data-autoload-count", number);

$("#price-div").html(result_sum);


break;
};

};


$(".buttons.pull-right .btn.btn-default").removeClass("pointer-events-none");
$(".valign span.btn.btn-default.btn-lg").removeClass("pointer-events-none");

  } else {
    $(".error-mes-block").fadeIn(500);
    setTimeout(function(){
  $(".error-mes-block").fadeOut(400);
}, 4000);




$(".buttons.pull-right .btn.btn-default").addClass("pointer-events-none");
$(".valign span.btn.btn-default.btn-lg").addClass("pointer-events-none");


};
});





$(".buttons.pull-right span.btn.btn-default").click(function(){
setTimeout(function(){
count_value_form = $(".counter.pull-left input.count").val();
sum_value_form = $(".buttons.pull-right .btn.btn-default").attr("data-autoload-summa");

$("#COUNT").val(count_value_form);

$("#SUMMA").val(sum_value_form);
}, 1200);


});

$("span.btn.btn-default.btn-lg").click(function(){
setTimeout(function(){
count_value_form = $(".counter.pull-left input.count").val();
sum_value_form = $(".buttons.pull-right .btn.btn-default").attr("data-autoload-summa");

$("#COUNT").val(count_value_form);

$("#SUMMA").val(sum_value_form);
}, 1200);


});


});

  </script>


</div>

<?//if($USER->IsAdmin()) { echo "<pre>"; print_r($arResult); echo "</pre>"; }?>