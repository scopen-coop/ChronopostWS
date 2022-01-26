<?php

/****************
 * file: wsregex.php
 * Collection of regular expressions to validate some input strings before sending requests to Chronopost WS
 *
 * date jan 2022
 * 
 ***************/

namespace ladromelaboratoire\chronopostws\utils;

class wsregex {
//shipping
	public CONST __reg_1234 = '/^(1|2|3|4)$/';

//header
	public CONST __reg_AccountNumber = '/^\d{8}$/'; 
	public CONST __reg_SubAccountNumber = '/^\d{3}$/';

//shipper
	
//customer

//skybill
	public CONST __reg_Content = '/^[a-zA-Z0-9 ]{0,45}$/';
	public CONST __reg_ObjectType = '/^(MAR|DOC)$/';
	public CONST __reg_ProductCodes = '/^(0|01|02|09|16|17|20|37|44|49|58|75|76|77|78|80|86|93|1F|1G|1K|1M|1N|1O|1P|1S|1T|1U|1V|1Y|2A|2B|2E|2F|2L|2M|2O|2P|2Q|2R|2S|3J|3K|3S|3T|3X|3Y|3Z|4I|4P|4Q|4R|4S|4T|4U|4V|4W|4X|5E|5H|5I|5J|5K|5L|5M|5N|5O|5P|5Q|5R|5S|5T|5U|5V|5X|5Y|5Z|8A|8B|8C|8D|8E|8F|8G|8H|8I|8J)$/';
	public CONST __reg_Services = '/^(0|1|6|101|136|237|327|328|345|346|907|920)$/';
	public CONST __reg_AsProducts = '/^(A02|A15)$/';
	public CONST __reg_SkybillNumber = '/^[A-Z]{2}\d{9}[A-Z]{2}$/';

// ref
	public CONST __reg_CustomerSkybillNumber = '/^[a-zA-Z0-9]{0,15}$/';
	public CONST __reg_Refs = '/^[a-zA-Z0-9 -]{0,35}$/';
	public CONST __reg_IdRelai = '/^[0-9]{4}[A-Za-z]{1}$/';
	
// Skybill Params
	public CONST __reg_SkybillMode = '/^(PDF|PPR|SPD|THE|Z2D|JSON|ZPL|SLT|XML|THEPSG|Z2DPSG)(?:\|(PDF|PPR|SPD|THE|Z2D|JSON|ZPL|SLT|XML|THEPSG|Z2DPSG))*$/';
	public CONST __reg_GetSkybill = '/^(PDF|PPR|SPD|THE|Z2D|JSON|ZPL|SLT|XML|THEPSG|Z2DPSG)$/';
	
// Customs
	public CONST __reg_clearanceCleared = '/^(N|F|E|T|I|H)$/';
	public CONST __reg_Eori = '/^[A-Z]{2}\d{9,15}$/';
	public CONST __reg_Incoterm = '/^(EXW|FCA|CPT|CIP|DAP|DPU|DDP|FAS|FOB|CFR|CIF)$/';
	public const __reg_VAT_EU = '/^(?:EU[ ]?\d{8,10}|DE[ ]?\d{9}|AT[ ]?U\d{8}|BE[ ]?0\d{9}|BG[ ]?\d{9,10}|CY[ ]?[0-9A-Z]{9}|HR[ ]?\d{11}|DK[ ]?\d{8}|ES[ ]?[0-9A-Z]{1}\d{7}[0-9A-Z]{1}|EE[ ]?\d{9}|FI[ ]?\d{8}|FR[ ]?\d{11}|EL[ ]?\d{9}|HU[ ]?\d{8}|IE[ ]?\d{7}[A-Z]{1,2}|IT[ ]?\d{11}|LV[ ]?\d{11}|LT[ ]?\d{9,12}|LU[ ]?\d{8}|MT[ ]?\d{8}|NL[ ]?[\dB]{11}|PL[ ]?\d{10}|PT[ ]?\d{9}|CZ[ ]?\d{8,10}|RO[ ]?\d{2,10}|GB[ ]?(?:\d{9}|\d{12}|GD[ ]?[0-4]\d\d|HA[ ]?[5-9]\d\d)|SK[ ]?\d{10}|SI[ ]?\d{8}|SE[ ]?\d{10}01)$/';
	public CONST __reg_CustomsRegime = '/^[a-zA-Z0-9]{0,3}$/';
	
// common
	public CONST __reg_AlphaNum = '/^[a-zA-Z0-9]*$/';
	public CONST __reg_AnyString = '/^[\S\s]*$/';
	public CONST __reg_PhoneNumber = '/^(\+[1-9][0-9]{0,2}|[0]{1})[1-9][0-9]{7,12}$/'; //local or international - digits only
	public CONST __reg_ZipCode = '/^[a-zA-Z0-9]{0,9}$/';
	public CONST __reg_PreAlert = '/^(0|11|22)$/';
	public CONST __reg_Civility = '/^(E|L|M)$/';
	public CONST __reg_Address = '/^[a-zA-Z0-9 ]{0,38}$/';
	public CONST __reg_ContactName = '/^[a-zA-Z0-9 ]{0,100}$/';
	public CONST __reg_CountryCode = '/^[A-Z]{2}$/';
	public CONST __reg_CountryName = '/^[a-zA-Z0-9 ]{0,50}$/';
	public const __reg_Email = "/^[A-Za-z0-9]+[A-Za-z0-9\/\-!&'*+%$#=?^_`{|}~\.]*@(?:[a-z0-9\-]+\.)+[a-z]{2,12}$/"; //long TLD proof
	public CONST __reg_YesNo = '/^(Y|N)$/';
	public CONST __reg_ContactType = '/^(1|2)$/';
	public CONST __reg_City = '/^[a-zA-Z0-9 ]{0,50}$/';
	public CONST __reg_CurrencyCode = '/^(EUR|USD|CNY)$/';
	public CONST __reg_DateTime = '/^2\d{3}-(?:(?:(?:01|03|05|07|08|10|12)-(?:[0-2]\d|3[0-1]))|(?:02-[0-2]\d)|(?:(?:04|06|09|11)-(?:[0-2]\d|30)))T(?:[0-1]\d|2[0-3]):[0-5]\d:[0-5]\d$/'; //validates date Y-m-dTH:i:s. Does not take care of leap year
	public CONST __reg_Date = '/^2\d{3}-(?:(?:(?:01|03|05|07|08|10|12)-(?:[0-2]\d|3[0-1]))|(?:02-[0-2]\d)|(?:(?:04|06|09|11)-(?:[0-2]\d|30)))$/'; //validates date Y-m-d. Does not take care of leap year
	public CONST __reg_Hour = '/^(?:[0-1]\d|2[0-3])$/';
	public CONST __reg_012 = '/^(0|1|2)$/';
	public CONST __reg_01 = '/^(0|1)$/';
	public CONST __reg_WsVersion = '/^(1|2|3|4|5|6|7)$/';
	public CONST __reg_string30 = '/^[a-zA-Z\d ]{0,30}$/';
	public CONST __reg_string100 = '/^[a-zA-Z\d ]{1,100}$/';
	public CONST __reg_string200 = '/^[a-zA-Z\d ]{1,200}$/';
	public CONST __reg_locale = '/^(sq_AL|ar_DZ|ar_BH|ar_EG|ar_IQ|ar_JO|ar_KW|ar_LB|ar_LY|ar_MA|ar_OM|ar_QA|ar_SA|ar_SD|ar_SY|ar_TN|ar_AE|ar_YE|be_BY|bn_IN|bn_BD|bg_BG|ca_ES|zh_CN|zh_HK|zh_SG|zh_TW|hr_HR|cs_CZ|da_DK|nl_BE|nl_NL|en_AU|en_CA|en_IN|en_IE|en_MT|en_NZ|en_PH|en_SG|en_ZA|en_GB|en_US|et_EE|fi_FI|fr_BE|fr_CA|fr_FR|fr_LU|fr_CH|de_AT|de_DE|de_LU|de_CH|el_CY|el_GR|iw_IL|hi_IN|hu_HU|is_IS|in_ID|ga_IE|it_IT|it_CH|ja_JP|ja_JP_JP|ko_KR|lv_LV|lt_LT|mk_MK|ms_MY|mt_MT|no_NO|no_NO_NY|pl_PL|pt_BR|pt_PT|ro_RO|ru_RU|sr_BA|sr_ME|sr_CS|sr_RS|sk_SK|sl_SI|es_AR|es_BO|es_CL|es_CO|es_CR|es_DO|es_EC|es_SV|es_GT|es_HN|es_MX|es_NI|es_PA|es_PY|es_PE|es_PR|es_ES|es_US|es_UY|es_VE|sv_SE|th_TH|th_TH_TH|tr_TR|uk_UA|vi_VN)$/';
	
}
?>