<?php

namespace Fantamanajer\Controllers;

use \Fantamanajer\Models as Models;

class PagesController extends ApplicationController {

    public function home() {
        $matchday = Models\Score::getMatchdayWithScores();
        $bestPlayer = NULL;
        $bestPlayers = NULL;

        if ($matchday > 0) {
            foreach ($this->roles as $key => $role) {

                $bestPlayers[$key] = Models\Member::getBestByMatchdayIdAndRole($matchday, $role);
                $bestPlayer[$key] = array_shift($bestPlayers[$key]);
            }
        }

        $articles = Models\Article::getLast(1);
        /* if($articoli != FALSE)
          foreach ($articoli as $key => $val)
          $articoli[$key]->text = Models\Emoticon::replaceEmoticon($val->testo,EMOTICONSURL); */

        $events = Models\Event::getEvents(NULL, NULL, 0, 5);

        $this->templates['content']->assign('teams', Models\Team::getByField('championship_id', 1));
        $this->templates['content']->assign('matchday', $matchday);
        $this->templates['content']->assign('bestPlayer', $bestPlayer);
        $this->templates['content']->assign('bestPlayers', $bestPlayers);
        $this->templates['content']->assign('articles', $articles);
        $this->templates['content']->assign('events', $events);
    }

    public function about() {
        
    }

    public function download() {
        if ($this->request->getParam('type') == 'csv') {
            $filesVoti = \Fantamanajer\Lib\FileSystem::getFileIntoFolder(VOTICSVDIR);
        } else {
            $filesVoti = \Fantamanajer\Lib\FileSystem::getFileIntoFolder(VOTIXMLDIR);
        }
        sort($filesVoti);
        
        $this->templates['content']->assign('filesVoti', $filesVoti);
    }

    public function buildDownload() {
        if ($this->request->getParam('giornata') != NULL && $this->request->getParam('type') != NULL) {
            $path = ($this->request->getParam('type') == 'csv') ? VOTICSVDIR : VOTIXMLDIR;
            if ($this->request->getParam('giornata') == "all") {
                $createZip = new \CreateZip();
                $path = $createZip->createZipFromDir($path, 'voti' . strtoupper($this->request->getParam('type')));
                $createZip->forceDownload($path, "voti" . strtoupper(Request::getInstance()->get('type')) . ".zip");
                @unlink($path);
            } else {
                header("Content-type: text/csv");
                header("Content-Disposition: attachment;filename=" . basename(Request::getInstance()->get('giornata')));
                header("Content-Transfer-Encoding: binary");
                header("Expires: 0");
                header("Pragma: no-cache");
                readfile($path . Request::getInstance()->get('giornata'));
            }
            die();
        }
    }

}

