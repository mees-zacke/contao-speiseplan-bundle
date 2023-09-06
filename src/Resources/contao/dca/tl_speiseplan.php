<?php
use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_speiseplan'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'enableVersioning' => true,
        'switchToEdit'     => true,
        'markAsCopy' => 'name',
        'ctable' => ['tl_speiseplan_week'],
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
			'fields'                  => array('name'),
			'flag'                    => DataContainer::SORT_INITIAL_LETTER_ASC,
			'panelLayout'             => 'filter;search,limit'
        ],
        'label' => [
			'fields'                  => array('name'),
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
				'href'                => 'table=tl_speiseplan_week',
				'icon'                => 'edit.svg'
			),
			'editheader' => array
			(
				'href'                => 'act=edit',
				'icon'                => 'header.svg',
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
        		'default'                     => '{speiseplan_legend},name;{newsletter_legend},about,senderName,senderEmail,text,mailTemplate;{publish_legend},published',
    ],
    'fields' => [
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default 0"
		),
		'name' => array
		(
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'about' => array
		(
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(255) NULL default ''"
		),
		'senderName' => array
		(
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NULL default ''"
		),
		'senderEmail' => array
		(
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NULL default ''"
		),
		'text' => array
		(
		    'exclude' => true,
		    'search' => true,
		    'inputType' => 'textarea',
		    'eval' => ['tl_class'=>'clr long','rte'=>'tinyMCE'],
		    'sql' => "text NULL default ''",
		),
		'mailTemplate' => array
		(
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
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
		)
    ],
];