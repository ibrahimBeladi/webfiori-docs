<?php
namespace webfiori\views;
use webfiori\entity\Page;
use webfiori\WebFiori;
use phpStructs\html\ListItem;
use phpStructs\html\Anchor;
use phpStructs\html\HTMLNode;
use phpStructs\html\PNode;
/**
 * Description of WebFioriPage
 *
 * @author Ibrahim
 */
class WebFioriPage {
    /**
     * Creates new instance of the class.
     * @param array $options An associative array of options. 
     * Available options are:
     * <ul>
     * <li><b>title</b>: The title of the page. If not provided, the value 
     * 'WebFiori Page' is used.</li>
     * <li><b>description</b>: The description of the page. If not provided, 
     * the global description which is stored in the class 'SiteConfig' is 
     * used.</li>
     * <li><b>site-name</b>: The name of the website. If not provided, 
     * the global website which is stored in the class 'SiteConfig' is 
     * used.</li>
     * <li><b>canonical</b>: The canonical link of the page.</li>
     * </ul>
     */
    public function __construct($options=array()) {
        Page::theme('WebFiori V108');
        if(isset($options['title'])){
            Page::title($options['title']);
        } else {
            Page::title('WebFiori Page');
        }
        if(isset($options['description'])){
            Page::description($options['description']);
        } else {
            Page::description(WebFiori::getSiteConfig()->getDescriptions()['EN']);
        }
        if(isset($options['canonical'])){
            Page::canonical($options['canonical']);
        }
        if(isset($options['site-name'])){
            Page::siteName($options['site-name']);
        } else {
            Page::siteName(WebFiori::getSiteConfig()->getWebsiteNames()['EN']);
        }
        Page::lang('EN');
    }
    /**
     * Creates HTML node of type 'img'
     * @param string $src The value of the attribute 'src'.
     * @param string $alt The value of the attribute 'alt'.
     * @return HTMLNode
     */
    public function createImag($src,$alt='') {
        $img = new HTMLNode('img', FALSE);
        $img->setAttribute('src', $src);
        $img->setAttribute('alt', $alt);
        $img->setStyle([
            'height'=>'auto',
            'max-width'=>'100%',
            'border' => '1px solid'
        ]);
        return $img;
    }
    public function getWFBG() {
        $img = $this->createImag('assets/images/WFLogo512.png');
        $img->setStyle([
            'background-position' => 'left',
            'opacity' => '0.3',
            'position' => 'fixed',
            'top' => 0,
            'left' => '50px',
            'z-index' => -1,
        ]);
        return $img;
    }
    /**
     * 
     * @param type $link
     * @param type $label
     * @return ListItem
     */
    public function createLinkListItem($link,$label,$target='_self') {
        $li00 = new ListItem();
        $link00 = new Anchor($link, $label,$target);
        $li00->addChild($link00);
        return $li00;
    }
    /**
     * Creates new paragraph node.
     * @param string $text The text that will be shown in the paragraph body.
     * @return PNode An object of type 'PNode'.
     */
    public function createParagraph($text) {
        $p = new PNode();
        $p->addText($text,array(
            'esc-entities'=>FALSE
        ));
        return $p;
    }
    /**
     * Creates HTMLNode of type 'section' with a heading.
     * @param string $title The title that will be in the heading node.
     * @param int $hLevel Heading level. The method will only accepts 
     * 1 up to 6 as value. If invalid value is provided, 1 is used as default.
     * @param string $secId The value of the attribute 'id' for the 
     * 'h' element. Default is null.
     * @return HTMLNode
     */
    public function createSection($title,$hLevel=3,$secId=null) {
        $sec = Page::theme()->createHTMLNode([
            'type'=>'section',
            'title'=>$title,
            'element-id'=>$secId,
            'h-level'=>$hLevel
        ]);
        
        if($sec == null){
            $sec = new HTMLNode('section');
            $hLevelX = $hLevel > 0 && $hLevel < 7 ? $hLevel : 1;
            $h = new HTMLNode('h'.$hLevelX);
            $h->addTextNode($title);
            $sec->addChild($h);
            if($secId !== null){
                $h->setID($secId);
            }
        }
        return $sec;
    }
    /**
     * 
     * @param string $title
     * @param string $ans
     * @return HTMLNode
     */
    public function createQuestionBox($title, $ans) {
        $questionSec = $this->createSection($title, 4);
        $questionSec->setClassName('question-box', false);
        $questionSec->setAttributes([
            'itemscope', 
            'itemtype'=>"http://schema.org/Question"
        ]);
        $questionSec->getChild(0)->setAttribute('itemprop', 'name');
        $answerBox = new HTMLNode();
        $answerBox->setAttributes([
            'itemprop'=>"suggestedAnswer acceptedAnswer",
            'itemscope',
            'itemtype'=>"http://schema.org/Answer"
        ]);
        $answerBox->setClassName('answer-box');
        $answerTxt = new HTMLNode();
        $answerTxt->setClassName('answer-text');
        $answerTxt->setAttribute('itemprop',"text");
        $answerBox->addChild($answerTxt);
        $answerBox->addTextNode($ans, false);
        $questionSec->addChild($answerBox);
        return $questionSec;
    }
    /**
     * Creates a generic HTMLNode based on the loaded theme.
     * @param array $options An array of options. The options depends on the 
     * loaded theme.
     * @return HTMLNode
     */
    public function createNode($options) {
        return Page::theme()->createHTMLNode($options);
    }
    public function displayView() {
        Page::render();
    }
    
}
