<?php


namespace MeesZacke\ContaoSpeiseplanBundle\Module;
use Contao\System;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\Model\Collection;
use Contao\FrontendTemplate;
use MeesZacke\ContaoSpeiseplanBundle\Model\SpeiseplanModel;
use MeesZacke\ContaoSpeiseplanBundle\Model\SpeiseplanWeekModel;
use MeesZacke\ContaoSpeiseplanBundle\Model\SpeiseplanDayModel;
use MeesZacke\ContaoSpeiseplanBundle\Model\SpeiseplanMenuModel;

class SpeiseplanModuleParse extends \Module{
    protected function compile(){
        //
    }

    public function getDays($weeks,$tstamp=0,$limit=0,$offset=0,$sorting='ASC'){

        $dayData = SpeiseplanDayModel::findBy(['pid IN (' . implode(',', $weeks) . ')','date > ?'], [$tstamp],['limit'=>$limit,'offset'=>$offset,'order'=>'date '.$sorting]);

        $days = [];

        if ($dayData !== null){
            foreach ($dayData as $dayData){
               $day['id'] = $dayData->id;
               $day['pid'] = $dayData->pid;
               $day['name'] = $dayData->name;
               $day['date'] = $dayData->date;

               $menuData = SpeiseplanMenuModel::findBy('pid',$dayData->id);
               if ($menuData !== null){
                   $menus = [];
                   foreach ($menuData as $menuData){
                    if ($menuData->menu !== null){
                        $menuAlias = System::getContainer()->get('contao.slug')->generate($menuData->menu);
                        $menus[$menuAlias] = $menuData->text;
                    }
                   }

                   $day['menus'] = $menus;
               } else{
                $day['menus'] = [];
               }

               $days[] = $day;
            };
        };

        return $days;
    }

    public function parseWeek($arrData,$weeks,$limit,$offset){
		$objTemplate = new FrontendTemplate($this->speiseplan_template ?: 'speiseplan_table');
		$objTemplate->setData($arrData->row());

        $objTemplate->week = $arrData->week;


        $speiseplan = SpeiseplanModel::findBy('id',$arrData->pid);
        $menuList = \StringUtil::deserialize($speiseplan->menuList);
        $menus = [];
        if ($menuList !== null){
            foreach($menuList as $menu){
                $menuAlias = System::getContainer()->get('contao.slug')->generate($menu);
                $menus[$menuAlias] = $menu;
            }
        }
        $objTemplate->menus = $menus;

        $objTemplate->days = $this->getDays($weeks);

        return $objTemplate->parse();
    }

    public function parseDays($weeks,$limit,$offset,$sorting){
		$objTemplate = new FrontendTemplate($this->speiseplan_template ?: 'speiseplan_table');

        $objTemplate->weeks =  implode(',',$weeks);

        $speiseplan = SpeiseplanModel::findBy('id',$arrData->pid);
        $menuList = \StringUtil::deserialize($speiseplan->menuList);
        $menus = [];
        if ($menuList !== null){
            foreach($menuList as $menu){
                $menuAlias = System::getContainer()->get('contao.slug')->generate($menu);
                $menus[$menuAlias] = $menu;
            }
        }
        $objTemplate->menus = $menus;

        $dayExpired = time();
        $dayExpired = $dayExpired - (24 * 60 * 60);

        $objTemplate->days = $this->getDays($weeks,$dayExpired,$limit,$offset,$sorting);

        if (!empty($objTemplate->days)){
        return $objTemplate->parse();
        } else {
            return false;
        }
    }


    public function parseArticles($arrData,$listType='weekly',$limit,$offset,$sorting){
        $arrArticles = [];
        if ($listType === 'weekly'){
            foreach ($arrData as $index => $article){
                if ($limit > 0){
                    if ($index >= $offset && $index < $offset + $limit){
                        $week = [];
                        $week[] = $article->id;
                        $arrArticles[] = static::parseWeek($article,$week,$limit,$offset);
                    } else{
                        return [];
                    }
                } else {
                    if ($index >= $offset){
                        $week = [];
                        $week[] = $article->id;
                        $arrArticles[] = static::parseWeek($article,$week,$limit,$offset);
                    } else{
                         return [];
                     }
                }
            }
        } else if($listType === 'daily'){
                foreach ($arrData as $week){
                    $weeks[] = $week->id;
                }
            if (static::parseDays($weeks,$limit,$offset,$sorting)){
                $arrArticles[] = static::parseDays($weeks,$limit,$offset,$sorting);
            } else{
                return [];
            }
        }
        return $arrArticles;
    }
}