<?php


namespace MeesZacke\ContaoSpeiseplanBundle\Module;

use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\Model\Collection;
use Contao\FrontendTemplate;
use MeesZacke\ContaoSpeiseplanBundle\Model\SpeiseplanWeekModel;
use MeesZacke\ContaoSpeiseplanBundle\Model\SpeiseplanTagModel;

class SpeiseplanModuleParse extends \Module{
    protected function compile(){
        //
    }

    public function getDays($weeks,$tstamp=0,$limit=0,$offset=0,$sorting='ASC'){

        $dayData = SpeiseplanTagModel::findBy(['pid IN (' . implode(',', $weeks) . ')','date > ?'], [$tstamp],['limit'=>$limit,'offset'=>$offset,'order'=>'date '.$sorting]);

        $days = [];

        if ($dayData !== null){
            foreach ($dayData as $dayData){
               $day['id'] = $dayData->id;
               $day['pid'] = $dayData->pid;
               $day['name'] = $dayData->name;
               $day['date'] = $dayData->date;
               $day['menu1'] = $dayData->menu1;
               $day['menu2'] = $dayData->menu2;
               $days[] = $day;
            };
        };

        return $days;
    }

    public function parseWeek($arrData,$weeks,$limit,$offset){
		$objTemplate = new FrontendTemplate($this->speiseplan_template ?: 'speiseplan_table');
		$objTemplate->setData($arrData->row());

        $objTemplate->week = $arrData->week;

        $objTemplate->test = $arrData;
        $objTemplate->days = $this->getDays($weeks);

        return $objTemplate->parse();
    }

    public function parseDays($weeks,$limit,$offset,$sorting){
		$objTemplate = new FrontendTemplate($this->speiseplan_template ?: 'speiseplan_table');

        $objTemplate->weeks =  implode(',',$weeks);

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