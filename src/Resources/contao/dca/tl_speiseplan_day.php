<?php
use Contao\Database;
use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_speiseplan_day'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'ptable' => 'tl_speiseplan_week',
        'ctable' => ['tl_speiseplan_menu'],
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
			'fields'                  => array('date'),
			'headerFields'            => ['week','startDate'],
			'flag'                    => 7,
			'panelLayout'             => 'filter;search,limit'
        ],
        'label' => [
			'fields'                  => array('dateClean','name'),
			'format'                  => '%s <span style="color:#999;padding-left:3px">[%s]</span>'
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
				'href'                => 'table=tl_speiseplan_menu',
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
        		'default'                     => '{date_legend},date,name,dateClean;{expert_legend},cssID;{publish_legend},invisible,start,stop',
    ],
    'fields' => [
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'              => 'tl_speiseplan_week.id',
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
			'eval'                    => array('mandatory' => true,'rgxp'=>'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"		),
			'save_callback' => array
			(
				array('tl_speiseplan_day', 'generateDate')
			),

		'dateClean' => array
		(
			'sql'                     => "varchar(255)  NULL default ''",
		),

		'name' => array
		(
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255)  NULL default ''",
			'save_callback' => array
			(
				array('tl_speiseplan_day', 'generateDay')
			),
		),
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

class tl_speiseplan_day extends Backend
{
    public function generateDay($varValue, DataContainer $dc)
    {
        if (!$varValue){
            $date = $dc->activeRecord->date;
            $format = new \IntlDateFormatter($GLOBALS['TL_LANGUAGE'],NULL,NULL);
            $format->setPattern('EEEE');
            $day = $format->format($date);
            $varValue = $day;
        }
        return $varValue;
    }
    public function generateDate($varValue, DataContainer $dc)
    {
        $date = $dc->activeRecord->date;
        $date = date('d.m.Y',$date);
        $id = $dc->activeRecord->id;
        $sql = 'UPDATE `tl_speiseplan_day` SET `dateClean` = ? WHERE `id` = ?';
        $this->Database->prepare($sql)->execute($date,$id);
        return $varValue;
    }
};