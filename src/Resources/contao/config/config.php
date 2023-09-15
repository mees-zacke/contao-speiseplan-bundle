<?php

$GLOBALS['BE_MOD']['content']['speiseplan'] = [
    'tables' => ['tl_speiseplan','tl_speiseplan_week','tl_speiseplan_tag']
];

$GLOBALS['FE_MOD']['application']['speiseplan'] = 'MeesZacke\ContaoSpeiseplanBundle\Module\SpeiseplanModule';

$GLOBALS['TL_MODELS']['tl_speiseplan'] = 'MeesZacke\\ContaoSpeiseplanBundle\\Model\\SpeiseplanModel';
$GLOBALS['TL_MODELS']['tl_speiseplan_week'] = 'MeesZacke\\ContaoSpeiseplanBundle\\Model\\SpeiseplanWeekModel';
$GLOBALS['TL_MODELS']['tl_speiseplan_tag'] = 'MeesZacke\\ContaoSpeiseplanBundle\\Model\\SpeiseplanTagModel';