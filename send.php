<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?
$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
?>

<div class="news-list" style="display: block;">

<?
$arSort = Array("SORT"=>"ASC");
$arSelect = Array("IBLOCK_ID","ID","PROPERTY_NAME","PROPERTY_QUESTION","PROPERTY_ANSWER","PROPERTY_PRODUCT","DATE_ACTIVE_FROM");
$arFilter = Array("IBLOCK_ID" => 18, "ACTIVE"=>"Y");

$res = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement()){
$arFields = $ob->GetFields();
?>
	<?if ($arFields['PROPERTY_PRODUCT_VALUE'] == $GLOBALS['aidi']):?>
		<div style="border-style:solid;border-color:#ECEFF1;padding-left:20px;padding-right:20px;padding-top:20px;">
			<b><?=$arFields['PROPERTY_NAME_VALUE']?></b>
			<p style="float:right;"><?=$arFields["DATE_ACTIVE_FROM"]?></p>
			<br>
			<br>
			<em>Вопрос:</em>
			<p><?=$arFields['PROPERTY_QUESTION_VALUE']?></p>
			<em style="color: #E9467C;">Ответ:</em>
			<p style="color: #E9467C;"><?=$arFields['PROPERTY_ANSWER_VALUE']?></p>
		</div>
		<br/>
	<?endif;?>
<?}?>

<div class="content_otziv_form">
	<form id="request-order-form-1" action="" method="POST">
		<label class="label_1">
			<p>Ваше имя:</p>
			<input type="text"  name="NAME" placeholder="имя" required="required">
		</label>
		<label class="label_2">
			<p>Ваш адрес электронной почты:</p>
			<input type="text"  name="MAIL" placeholder="e-mail" required="required" >
		</label>
		<label class="label_3">
			<p>Ваш вопрос:</p>
			<textarea type="text"  name="QUESTION" placeholder="вопрос" required="required"></textarea>
		</label>
		<button name="question" type="submit" class="btn btn-small btn-dark-solid" style=" border: 0; width: 254px; height: 50px; border-radius: 6px; background: #e9467c; color: #fff; letter-spacing: 1px; font-size: 12px; font-family: 'Roboto_Medium'; text-transform: uppercase; cursor: pointer; float: left;">отправить вопрос</button>
	</form>
<?$submit = $_POST['question'];?>  
<?if(isset($submit)):?>
	<?CModule::IncludeModule('iblock');
		$el = new CIBlockElement;
		//Свойства
		$PROP = array();
		$PROP['NAME'] = $_POST['NAME'];
		$PROP['MAIL'] = $_POST['MAIL'];
		$PROP['QUESTION'] = $_POST['QUESTION'];
		$PROP['PRODUCT'] = $GLOBALS['aidi'];

		//Основные поля элемента
		$fields = array(
			"DATE_CREATE" => date("d.m.Y H:i:s"), //Передаем дата создания
			"CREATED_BY" => $GLOBALS['USER']->GetID(),    //Передаем ID пользователя кто добавляет
			"IBLOCK_SECTION" => false, //ID разделовибо нет отдельного раздела
			"IBLOCK_ID" => 18,
			"PROPERTY_VALUES" => $PROP, // Передаем массив значении для свойств
			"PRODUCT" => $GLOBALS['aidi'],
			"NAME" => $_POST['NAME'],
			"MAIL" => $_POST['MAIL'],
			"URL" => SITE_SERVER_NAME."/bitrix/admin/iblock_list_admin.php?IBLOCK_ID=".$arResult["ID"]."&type=".$arResult["IBLOCK_TYPE_ID"]."&lang=ru&find_section_section=0",
			"TEXT_MESSAGE" => $_POST['QUESTION'],
			"ACTIVE" => "N", //поумолчанию делаем активным или ставим N для отключении поумолчанию
	);?>
		<?$event = new CEvent;
		CEvent::Send("USER_QUESTION", SITE_ID, $fields, "N", 49);
		$el->Add($fields);
		$arMail = array(
			"TEXT_MESSAGE" => $_POST['NAME'].$_POST['MAIL'].$_POST['QUESTION']
		);
		CEvent::Send("USER_QUESTION", $arMail);?>
<?endif;?>
</div>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
