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
use phpStructs\html\CodeSnippet;
/**
 * Description of ClassHTMLDocView
 *
 * @author Ibrahim
 */
class ClassHTMLDocView extends ThemesLearnView{
    public function __construct() {
        parent::__construct(array(
            'active-aside'=>3,
            'title'=>'The Class \'HTMLDoc\'',
            'description'=>'The class HTMLDoc represents HTML document. It can be used '
            . 'to change many attributes of page\'s DOM.'
        ));
        Page::document()->getHeadNode()->addCSS('themes/webfiori/css/code-theme.css');
        Page::insert($this->createParagraph('The class <a href="docs/phpStructs/html/HTMLDoc" '
                . 'target="_blank">HTMLDoc</a> represent HTML 5 document. By default, the document that will be generated '
                . 'by the class will look like the following.'));
        $code = new CodeSnippet();
        $code->setTitle('HTML Code');
        $code->getCodeElement()->setClassName('language-html');
        $doc = new HTMLDoc();
        $code->setCode($doc->toHTML());
        Page::insert($code);
        Page::insert($this->createParagraph('The content of the &lt;head&gt; tag can be controlled using an '
                . 'instance of the class <a href="docs/phpStructs/html/HeadNode" target="_blank">HeadNode</a> which will be introduced later. '
                . 'To get access to the head node, it is possible to use the method '
                . '<a href="docs/phpStructs/html/HTMLDoc#getHeadNode" target="_blank">HTMLDoc::getHeadNode()</a>.'));
        Page::insert($this->createParagraph('The content of the &lt;body&gt; tag can be controlled using an '
                . 'instance of the class <a href="docs/phpStructs/html/HTMLNode" target="_blank">HTMLNode</a>. '
                . 'To get access to the body, the method '
                . '<a href="docs/phpStructs/html/HTMLDoc#getBody" target="_blank">HTMLDoc::getBody()</a> can be used. '
                . 'If the developer would like to add child nodes directly to the body node without using an instance of the '
                . 'body, it is possible to use the method <a href="docs/phpStructs/html/HTMLDoc#addChild" target="_blank">HTMLDoc::addChild()</a> to perform this task.'));
        $this->setPrevTopicLink('learn/topics/themes/class-HTMLNode', 'The class \'HTMLNode\'');
        $this->setNextTopicLink('learn/topics/themes/class-HeadNode', 'The class \'HeadNode\'');
        $this->displayView();
    }
}
new ClassHTMLDocView();
