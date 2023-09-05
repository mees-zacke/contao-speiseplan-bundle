<?php

use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_speiseplan_tag'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'ptable' => 'tl_speiseplan',
        'enableVersioning' => true,
        'switchToEdit'     => true,
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
			'mode'                    => DataContainer::MODE_SORTED,
			'fields'                  => array('date'),
			'flag'                    => DataContainer::SORT_INITIAL_LETTER_ASC,
			'panelLayout'             => 'filter;search,limit'
        ],
        'label' => [
			'fields'                  => array('date'),
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
        		'default'                     => '{date_legend},date;{menu_legend},menu1,menu2;{expert_legend},cssID;{publish_legend},published,start,stop',
    ],
    'fields' => [
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'              => 'tl_speiseplan.week',
			'sql'                     => "int(10) unsigned NOT NULL default 0",
			'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default 0"
		),
		'date' => array
		(
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'menu1' => [
		    'exclude' => true,
		    'search' => true,
		    'inputType' => 'textarea',
		    'eval' => ['mandatory' => true,'tl_class'=>'clr long','rte'=>'tinyMCE'],
		    'sql' => "text NOT NULL default ''",
		],
		'menu2' => [
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
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'published' => array
		(
			'exclude'                 => true,
			'toggle'                  => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'start' => array
		(
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'stop' => array
		(
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		)
    ],
];