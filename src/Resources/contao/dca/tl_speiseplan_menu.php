<?php
use Contao\Database;
use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_speiseplan_menu'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'ptable' => 'tl_speiseplan_day',
        'enableVersioning' => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
			)
        )
    ],
    'list' => [
        'sorting' => [
			'mode'                    => 4,
			'fields'                  => array('menu'),
			'headerFields'            => ['name','date'],
			'flag'                    => 11,
			'panelLayout'             => 'filter;search,limit'
        ],
        'label' => [
			'fields'                  => array('menu'),
			'format'                  => '%s'
        ],
		'global_operations' => [
			'all' => [
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			]
		],
		'operations' => array
		(
			'edit' => array
			(
				'href'                => 'act=edit',
				'icon'                => 'edit.svg'
			),
			'copy' => array
			(
				'href'                => 'act=copy',
				'icon'                => 'copy.svg',
			),
			'delete' => array
			(
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"',
			),
			'toggle' => array
			(
				'href'                => 'act=toggle&amp;field=published',
				'icon'                => 'visible.svg',
				'showInHeader'        => true
			)
		)
    ],
    'palettes' => [
        		'default'                     => '{menu_legend},menu,text;{expert_legend},cssID;{publish_legend},invisible',
    ],
    'fields' => [
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'              => 'tl_speiseplan_tag.id',
			'sql'                     => "int(10) unsigned NOT NULL default 0",
			'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default 0"
		),
        'menu' => [
            'exclude'                 => true,
            'inputType'               => 'radio',
            'options_callback'        => array('tl_speiseplan_menu', 'getMenus'),
            'eval'                    => array('multiple'=>true, 'mandatory'=>false),
            'sql'                     => "blob NULL"
        ],
		'text' => [
		    'exclude' => true,
		    'search' => true,
		    'inputType' => 'textarea',
		    'eval' => ['mandatory' => true,'tl_class'=>'clr long','rte'=>'tinyMCE'],
		    'sql' => "text NOT NULL default ''",
		],
		'cssID' => array
		(
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(255) NULL default ''"
		),
		'invisible' => array
		(
			'exclude'                 => true,
			'toggle'                  => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'sql'                     => "char(1) COLLATE ascii_bin NOT NULL default ''"
		),
    ],
];


class tl_speiseplan_menu extends Backend
{
	public function __construct()
	{
		parent::__construct();
		$this->import(BackendUser::class, 'User');
	}

    public function getMenus (DataContainer $dc){

		if (!$this->User->isAdmin && !is_array($this->User->news))
		{
			return array();
		}

		$day = $this->Database->execute("SELECT pid FROM tl_speiseplan_menu WHERE id = '$dc->id'");
		$week = $this->Database->execute("SELECT pid FROM tl_speiseplan_day WHERE id = '$day->pid'");
		$speiseplan = $this->Database->execute("SELECT pid FROM tl_speiseplan_week WHERE id = '$week->pid'");

		$arrArchives = array();
		$objArchives = $this->Database->execute("SELECT menuList FROM tl_speiseplan WHERE id = '$speiseplan->pid'");

        $menus = \StringUtil::deserialize($objArchives->menuList);

        foreach($menus as $menu){
           $arrArchives[$menu] = $menu;
        }

		return $arrArchives;

    }
}