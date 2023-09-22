<?php

namespace MeesZacke\ContaoSpeiseplanBundle\Module;

use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\Model\Collection;
use Contao\FrontendTemplate;
use MeesZacke\ContaoSpeiseplanBundle\Model\SpeiseplanWeekModel;
use MeesZacke\ContaoSpeiseplanBundle\Model\SpeiseplanDayModel;
use MeesZacke\ContaoSpeiseplanBundle\Model\SpeiseplanModel;
use MeesZacke\ContaoSpeiseplanBundle\Model\SpeiseplanMenuModel;
use Contao\Model\ModuleModel;
use Haste\Form\Form;
use Haste\DateTime\DateTime;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;


class SpeiseplanExcelImportModule extends \Module
{
    /**
     * @var string
     */
    protected $strTemplate = 'mod_speiseplan_excelimport';

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

    protected function excelImport(){
    header('Content-Type: text/html; charset=utf-8');

        $id = $this->speiseplan_excel;
        $dateCell = $this->speiseplan_dateCell;
        $startCell = $this->speiseplan_startCell;
        $endCell = $this->speiseplan_endCell;
        $uploadFile = 'xls_upload';
        $time = time();

        $speiseplan = SpeiseplanModel::findBy('id',$id);

        require_once 'vendor/autoload.php';

        // Allowed mime types
        $excelMimes = array('text/xls', 'text/xlsx', 'application/excel', 'application/vnd.msexcel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // Validate whether selected file is a Excel file
        if(!empty($_FILES[$uploadFile]['name']) && in_array($_FILES[$uploadFile]['type'], $excelMimes)){
            if (is_uploaded_file($_FILES[$uploadFile]['tmp_name'])){
                $reader = new Xlsx();
                $spreadsheet = $reader->load($_FILES[$uploadFile]['tmp_name']);
                $worksheet = $spreadsheet->getActiveSheet();

                $startDate = $worksheet->getCell($dateCell)->getFormattedValue();


                if (strtotime($startDate)){
                  $startDate = new DateTime($startDate);
                  $tstamp = $startDate->getTimestamp();
                  $week = date('W',$tstamp);
                  $week = $GLOBALS['TL_LANG']['MSC']['speiseplanWeek'] . ' ' . $week;
                  $weekModel = SpeiseplanWeekModel::findBy(['startDate = ?','pid = ?'],[$tstamp,$id]);

                  if ($weekModel === null){
                      $weekModel = new SpeiseplanWeekModel;
                      $weekModel->pid = $speiseplan->id;
                      $weekModel->week = $week;
                      $weekModel->startDate = $tstamp;
                      $weekModel->published = 1;
                  }
                  $weekModel->tstamp = $time;
                  $weekModel->save();

                  $speiseplanDays = $worksheet->rangeToArray($startCell.':'.$endCell);

                  $dayCount = count($speiseplanDays[0]);
                  $menuStructure = \StringUtil::deserialize($this->speiseplan_menuList);

                  for ($i = 0;$i < $dayCount;$i++){
                    $day = $startDate->getTimestamp();
                    $day = $day + (24 * 60 * 60) * $i;
                    $dayDate = date('d.m.Y',$day);
                    $format = new \IntlDateFormatter(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2),NULL,NULL);
                    $format->setPattern('EEEE');
                    $dayName = $format->format($day);
                    $dayModel = SpeiseplanDayModel::findBy(['date = ?','pid = ?'], [$day,$weekModel->id]);
                    if ($dayModel === null){
                      $dayModel = new SpeiseplanDayModel;
                      $dayModel->pid = $weekModel->id;
                      $dayModel->date = $day;
                      $dayModel->name = $dayName;
                      $dayModel->dateClean = $dayDate;
                    }
                    $dayModel->tstamp = $time;
                    $dayModel->save();

                    foreach($speiseplanDays as $index => $row){
                        $menuStructure[$index]['content'] = $row[$i];
                    }

                    $menus = [];

                    foreach($menuStructure as $row){
                        if (!in_array($row['menuName'],$menus)){
                            $menus[$row['menuName']]['name'] = $row['menuName'];
                        }
                        $row['content'] = '<span class="' . $row['styleClass'] . '">' . $row['content'] . '</span><br>';
                        $menus[$row['menuName']]['content'] = $menus[$row['menuName']]['content'] . $row['content'];
                    }

                    foreach($menus as $menu){
                        $menuModel = SpeiseplanMenuModel::findBy(['menu = ?','pid = ?'], [$menu['name'],$dayModel->id]);
                        if ($menuModel === null){
                          $menuModel = new SpeiseplanMenuModel;
                          $menuModel->pid = $dayModel->id;
                          $menuModel->menu = $menu['name'];
                        }
                        $menuModel->text = $menu['content'];
                        $menuModel->tstamp = $time;
                        $menuModel->save();

                    }

                    $this->Template->success = "Import erfolgreich";

                  }


                } else {
                    $this->Template->error = "kein korrektes Startdatum";
                }
            }
        } else{
            $this->Template->error = 'Keine/Fehlerhafte Excel-Datei';
        }
    }


    protected function compile()
    {
        $speiseplanArr = \StringUtil::deserialize($this->speiseplan);
        $this->Template->speiseplan = $speiseplanArr;

        $structure = \StringUtil::deserialize($this->speiseplan_menuList);
        $this->Template->speiseplan_structure = $structure;

        $this->Template->dateCell = $this->speiseplan_dateCell;
        $this->Template->startCell = $this->speiseplan_startCell;


        $form = new Form('xls_import_form_' . $this->id, 'POST',fn() => static::excelImport());

        //$form->setFormActionFromUri('importExcel.php?url=' . $this->replaceInserttags('{{env::url}}') . '&id=' . $this->id . '&startCell=' . $this->speiseplan_startCell . '&dateCell=' . $this->speiseplan_dateCell);

        $form->addFormField('xls_upload', [
            'label' => [&$GLOBALS['TL_LANG']['MSC']['excelUploadLabel']],
            'inputType' => 'upload',
            'eval' => ['mandatory' => true,'extensions'=>'xls,xlsx', 'storeFile'=>true],

        ]);
        $form->addSubmitFormField('submit',$GLOBALS['TL_LANG']['MSC']['excelSubmitLabel']);

        $this->Template->form = $form;
    }
}