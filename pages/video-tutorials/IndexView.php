<?php
namespace webfiori\views;
use webfiori\entity\Page;
use phpStructs\html\UnorderedList;
use phpStructs\html\Anchor;
use phpStructs\html\HTMLNode;
/**
 * Description of ContributeView
 *
 * @author Eng.Ibrahim
 */
class ContributeView extends WebFioriPage{
    public function __construct() {
        parent::__construct(array(
            'title'=>'Video Tutorials',
            'description'=>'A video based tutorials which can help you '
            . 'in getting started with WebFiori Framework.'
        ));
        Page::insert($this->createParagraph('Here, you will find a set of '
                . 'usefule videos which can be very helpful. They explain '
                . 'most of the things that you need to get started with '
                . 'WebFiori Framework. The creator of the videos is the '
                . 'author of the video.'));
        $bodyRow = Page::theme()->createHTMLNode(['type'=>'row']);
        Page::insert($bodyRow);
        
        $bodyRow->addChild($this->_createVideoBox('Introduction','An introduction to WebFiori Framework.', 'rxfy1f0PGHw', 'intro'));
        $bodyRow->addChild($this->_createVideoBox('Installing WAMP Stack','How to install WAMP Stack and setup local development environment.', '5JhFgz8Iycw', 'installing-wamp-stack'));
        $bodyRow->addChild($this->_createVideoBox('Creating PHP Project','How to create a WebFiori Framework based PHP project using Apache NetBeans IDE.', 'h7tCZLfSvmE', 'creating-first-project'));
        $bodyRow->addChild($this->_createVideoBox('Routing - Part 1','Introduction to routing.', 'n7ZC-ti5XkM', 'routing-p1'));
        $bodyRow->addChild($this->_createVideoBox('Routing - Part 2','Different types of routes and how to create them.', 'UEQhVRVG7b4', 'routing-p2'));
        $bodyRow->addChild($this->_createVideoBox('Views - Part 1','Learn about the basic components of building HTML pages.', 'ny1nz_zfUs4', 'views-p1'));
        $bodyRow->addChild($this->_createVideoBox('Views - Part 2','An introduction to the class "Page".', 'sFdRiokvThg', 'views-p2'));
        $this->displayView();
    }
    /**
     * 
     * @param type $title
     * @param type $desc
     * @param type $vidId
     * @param type $vidPageLink
     * @return HTMLNode
     */
    private function _createVideoBox($title,$desc,$vidId,$vidPageLink){
        $link = Page::canonical().'/'.$vidPageLink;
        $vidRow = Page::theme()->createHTMLNode(['type'=>'col','size'=>3]);
        $vidRow->setStyle([
            'border'=>'1px solid'
        ]);
        $imgUrl = 'https://img.youtube.com/vi/'.$vidId.'/sddefault.jpg';
        $vidImg = new HTMLNode('img');
        $vidImg->setAttribute('src', $imgUrl);
        $vidImg->setStyle([
            'width'=>'110px'
        ]);
        $vidRow->addTextNode('<a href="'.$link.'">'.$vidImg.'</a>',false);
        $vidTitleContainer = new HTMLNode();
        $vidTitleContainer->addTextNode('<p><a href="'.$link.'"></p>'.$title.'</a>',false);
        $vidTitleContainer->addTextNode('<p>'.$desc.'</p>',false);
        $vidRow->addChild($vidTitleContainer);
        return $vidRow;
    }
}
new ContributeView();