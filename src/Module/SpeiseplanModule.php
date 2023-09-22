<?php

namespace MeesZacke\ContaoSpeiseplanBundle\Module;

use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\Model\Collection;
use Contao\FrontendTemplate;
use MeesZacke\ContaoSpeiseplanBundle\Model\SpeiseplanWeekModel;
use MeesZacke\ContaoSpeiseplanBundle\Model\SpeiseplanDayModel;
use MeesZacke\ContaoSpeiseplanBundle\Model\SpeiseplanModel;
use MeesZacke\ContaoSpeiseplanBundle\Model\SpeiseplanMenuModel;

class SpeiseplanModule extends SpeiseplanModuleParse
{



    /**
     * @var string
     */
    protected $strTemplate = 'mod_speiseplan';

    /**
     * Displays a wildcard in the back end.
     *
     * @return string
     */
    public function generate()
    {


        if (TL_MODE == 'BE') {
            $template = new \BackendTemplate('be_wildcard');

            $template->wildcard = '### Speiseplan ###';
            $template->title = $this->headline;
            $template->id = $this->id;
            $template->link = $this->name;
            $template->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id='.$this->id;

            return $template->parse();
        }
        return parent::generate();
    }

    /**
     * Generates the module.
     */
    protected function compile()
    {


        /*
            Get Weeks by chosen Speiseplans
        */
        $speiseplan = $this->speiseplan;
        $this->Template->speiseplan = $speiseplan;
		$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['emptySpeiseplan'];

        $listType = $this->speiseplan_listType;
        $sorting = $this->speiseplan_sorting;

        $speiseplan = SpeiseplanModel::findBy('id',$speiseplan);

        $menuStructure = \StringUtil::deserialize($speiseplan->menuList);
        $this->Template->speiseplan_menuList = $menuStructure;

        $weekExpired = time();
        $weekExpired = $weekExpired - (7 * 24 * 60 * 60);

        $speiseplanData = SpeiseplanWeekModel::findBy(['pid = ?','startDate > ?'], [$speiseplan,$weekExpired],['order' => 'startDate ' . $sorting]);

        $limit = $this->numberOfItems;
        $offset = $this->skipFirst;

        $this->Template->articles = [];

        if ($speiseplanData !== null){
                $this->Template->articles = $this->parseArticles($speiseplanData,$listType,$limit,$offset,$sorting);
        }


    }
}