<?php

$GLOBALS['BE_MOD']['content']['speiseplan'] = [
    'tables' => ['tl_speiseplan','tl_speiseplan_week','tl_speiseplan_day','tl_speiseplan_menu']
];

$GLOBALS['FE_MOD']['application']['speiseplan'] = 'MeesZacke\ContaoSpeiseplanBundle\Module\SpeiseplanModule';
$GLOBALS['FE_MOD']['application']['speiseplan_excelImport'] = 'MeesZacke\ContaoSpeiseplanBundle\Module\SpeiseplanExcelImportModule';

$GLOBALS['TL_MODELS']['tl_speiseplan'] = 'MeesZacke\\ContaoSpeiseplanBundle\\Model\\SpeiseplanModel';
$GLOBALS['TL_MODELS']['tl_speiseplan_week'] = 'MeesZacke\\ContaoSpeiseplanBundle\\Model\\SpeiseplanWeekModel';
$GLOBALS['TL_MODELS']['tl_speiseplan_day'] = 'MeesZacke\\ContaoSpeiseplanBundle\\Model\\SpeiseplanDayModel';
$GLOBALS['TL_MODELS']['tl_speiseplan_menu'] = 'MeesZacke\\ContaoSpeiseplanBundle\\Model\\SpeiseplanMenuModel';