<?php

$GLOBALS['TL_DCA']['tl_module']['palettes']['speiseplan'] =
    '{title_legend},name,headline,type;{speiseplan_legend},speiseplan,speiseplan_listType,speiseplan_sorting,numberOfItems,skipFirst;{protected_legend:hide},protected;{template_legend},speiseplan_template;{expert_legend:hide},guests,cssID,space'
;
$GLOBALS['TL_DCA']['tl_module']['palettes']['speiseplan_excelImport'] =
    '{title_legend},name,headline,type;{speiseplan_legend},speiseplan_excel,speiseplan_dateCell,speiseplan_startCell,speiseplan_endCell,speiseplan_menuList;{protected_legend:hide},protected;{template_legend},template;{expert_legend:hide},guests,cssID,space'
;


$GLOBALS['TL_DCA']['tl_module']['fields']['speiseplan'] = array
(
	'exclude'                 => true,
	'inputType'               => 'radio',
	'options_callback'        => array('tl_module_speiseplan', 'getSpeiseplan'),
	'eval'                    => array('multiple'=>true, 'mandatory'=>true),
	'sql'                     => "blob NULL"
);


$GLOBALS['TL_DCA']['tl_module']['fields']['speiseplan_listType'] = array
(
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => ['weekly','daily'],
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class' => 'clr w50'),
	'sql'                     => "varchar(255) NOT NULL default 'weekly'"
);


$GLOBALS['TL_DCA']['tl_module']['fields']['speiseplan_sorting'] = array
(
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => ['ASC', 'DESC'],
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class' => 'w50'),
	'sql'                     => "varchar(255) NOT NULL default 'ASC'"
);


$GLOBALS['TL_DCA']['tl_module']['fields']['speiseplan_template'] = array
(
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback' => static function ()
	{
		return Controller::getTemplateGroup('speiseplan_');
	},
	'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50 clr'),
	'sql'                     => "varchar(64) COLLATE ascii_bin NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['speiseplan_excel'] = array
(
	'exclude'                 => true,
	'inputType'               => 'radio',
	'options_callback'        => array('tl_module_speiseplan', 'getSpeiseplan'),
	'eval'                    => array('multiple'=>true, 'mandatory'=>true,'submitOnChange' => true),
	'sql'                     => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['speiseplan_dateCell'] = array
(
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true,'tl_class'=>'clr w50'),
	'sql'                     => "varchar(255) NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['speiseplan_startCell'] = array
(
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true,'tl_class'=>'clr w50'),
	'sql'                     => "varchar(255) NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['speiseplan_endCell'] = array
(
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true,'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NULL"
);



$GLOBALS['TL_DCA']['tl_module']['fields']['speiseplan_menuList'] = array
(
			'exclude'                 => true,
			'inputType'               => 'multiColumnWizard',
			'eval'                    => array('multiple'=>true, 'allowHtml'=>true, 'tl_class'=>'w50 clr',
                                      'columnFields' => [
                                        'menuName' =>[
                                            'label' => &$GLOBALS['TL_LANG']['tl_module']['menuName'],
                                            'inputType' => 'text',
                                        ],
                                        'styleClass' => [
                                            'label' => &$GLOBALS['TL_LANG']['tl_module']['styleClass'],
                                            'inputType' => 'text',
                                        ],
                                      ]
			),
			'sql'                     => "blob NULL"
);

class tl_module_speiseplan extends Backend
{
	public function __construct()
	{
		parent::__construct();
		$this->import(BackendUser::class, 'User');
	}

    public function getSpeiseplan (){

		if (!$this->User->isAdmin && !is_array($this->User->news))
		{
			return array();
		}

		$arrArchives = array();
		$objArchives = $this->Database->execute("SELECT id, name FROM tl_speiseplan ORDER BY name");

		while ($objArchives->next())
		{
            $arrArchives[$objArchives->id] = $objArchives->name;
		}

		return $arrArchives;

    }
    public function getMenusBySpeiseplan($dc){
    }
}