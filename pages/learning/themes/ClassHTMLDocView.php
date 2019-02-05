<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace webfiori\views\learn\themes;
use webfiori\WebFiori;
use phpStructs\html\HTMLDoc;
use webfiori\entity\Page;
use phpStructs\html\HTMLNode;
/**
 * Description of ClassHTMLDocView
 *
 * @author Ibrahim
 */
class ClassHTMLDocView extends ThemesLearnView{
    public function __construct() {
        parent::__construct(array(
            'title'=>'The Class \'HTMLDoc\'',
            'description'=>'',
            'canonical'=> WebFiori::getSiteConfig()->getBaseURL().'learn/topics/themes/class-HTMLDoc'
        ));
        
        $this->createParagraph('The class <a href="'.WebFiori::getSiteConfig()->getBaseURL().'docs/phpStructs/html/HTMLDoc" '
                . 'target="_blank">HTMLDoc</a> represent HTML 5 document. ');
        
        $this->setPrevTopicLink('learn/topics/themes/class-HTMLNode', 'The class \'HTMLNode\'');
        $this->setNextTopicLink('learn/topics/themes/class-HeadNode', 'The class \'HeadNode\'');
        $this->displayView();
    }
}
new ClassHTMLDocView();