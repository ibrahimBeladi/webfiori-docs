<?php
use webfiori\WebFiori;
use webfiori\entity\Page;
use phpStructs\html\JsCode;
use phpStructs\html\ListItem;
use phpStructs\html\Anchor;
use phpStructs\html\HeadNode;
use phpStructs\html\HTMLNode;
use phpStructs\html\PNode;
use phpStructs\html\UnorderedList;
use webfiori\apiParser\APITheme;
use webfiori\apiParser\NameSpaceAPI;
use webfiori\apiParser\FunctionDef;
use phpStructs\html\Label;
use webfiori\theme\LangExt;

class WebFioriAPITheme extends APITheme{
    public function __construct() {
        parent::__construct();
        $this->setAuthor('Ibrahim Ali');
        $this->setName('WebFiori API Theme');
        $this->setVersion('1.0');
        $this->setDescription('A theme that is used in API description pages.');
        $this->setImagesDirName('images');
        $this->setJsDirName('js');
        $this->setCssDirName('css');
        $this->addComponents(array(
            'WebFioriAPIGUI.php'
        ));
        $this->setBeforeLoaded(function(){
            $lang = WebFiori::getWebsiteController()->getSessionLang(TRUE);
            Page::lang($lang);
            if($lang == 'AR'){
                Page::dir('rtl');
            }
            else{
                Page::dir('ltr');
            }
        });
        $this->setAfterLoaded(function(){
            Page::lang('EN');
            Page::document()->getChildByID('main-content-area')->setClassName('pa-'.Page::dir().'-col-10');
            Page::document()->getChildByID('side-content-area')->setClassName('pa-'.Page::dir().'-col-2');
            Page::document()->getChildByID('page-body')->setClassName('pa-row');
            Page::document()->getChildByID('page-header')->setClassName('pa-row-np');
            Page::document()->getChildByID('page-footer')->setClassName('pa-row');
            //WebFioriGUI::createTitleNode();

            LangExt::extLang();
            $translation = Page::translation();
            //adding menu items 
            $mainMenu = Page::document()->getChildByID('main-menu');

            $item1 = new ListItem();
            $link1 = new Anchor($this->getBaseURL().'download', $translation->get('menus/main-menu/menu-item-1'));
            $item1->addChild($link1);
            $mainMenu->addChild($item1);

            $item2 = new ListItem();
            $link2 = new Anchor($this->getBaseURL().'docs/webfiori', $translation->get('menus/main-menu/menu-item-2'));
            $item2->addChild($link2);
            $mainMenu->addChild($item2);

            $item3 = new ListItem();
            $link3 = new Anchor($this->getBaseURL().'learn', $translation->get('menus/main-menu/menu-item-3'));
            $item3->addChild($link3);
            $mainMenu->addChild($item3);
            
            $item4 = new ListItem();
            $link4 = new Anchor($this->getBaseURL().'contribute', $translation->get('menus/main-menu/menu-item-4'));
            $item4->addChild($link4);
            $mainMenu->addChild($item4);

        });

    }
    public function getAsideNode() {
        $menu = new HTMLNode('div');
        return $menu;
    }
    public function createBodyNode(){
        $body = parent::createBodyNode();
        $body->setClassName('pa-row '.$body->getAttributeValue('class'));
        return $body;
    }
    public function createAttrsSummaryBlock(){
        $node = parent::createAttrsSummaryBlock();
        if($node !== NULL){
            $node->setClassName('pa-row '.$node->getAttributeValue('class'));
        }
        return $node;
    }
    public function createAttrsDetailsBlock(){
        $node = parent::createAttrsDetailsBlock();
        if($node !== NULL){
            $node->setClassName('pa-row '.$node->getAttributeValue('class'));
        }
        return $node;
    }
    public function createMethodsSummaryBlock(){
        $node = parent::createMethodsSummaryBlock();
        if($node !== NULL){
            $node->setClassName('pa-row '.$node->getAttributeValue('class'));
        }
        return $node;
    }
    public function createMethodsDetailsBlock(){
        $node = parent::createMethodsDetailsBlock();
        if($node !== NULL){
            $node->setClassName('pa-row '.$node->getAttributeValue('class'));
        }
        return $node;
    }
    public function getFooterNode() {
        $node = new HTMLNode('div');
        $socialMedia = new HTMLNode();
        $socialMedia->setClassName('pa-row');
        $socialMedia->setID('social-media-container');
        $socialMedia->setWritingDir(Page::dir());

        $facebookIcon = new HTMLNode('img', FALSE);
        $facebookIcon->setAttribute('src', Page::imagesDir().'/facebook.png');
        $facebookIcon->setClassName('social-media-icon');
        $facebookLink = new HTMLNode('a');
        $facebookLink->setAttribute('href', 'https://www.facebook.com/webfiori');
        $facebookLink->setAttribute('target', '_blank');
        $facebookLink->addChild($facebookIcon);
        $socialMedia->addChild($facebookLink);

        $twtrIcon = new HTMLNode('img', FALSE);
        $twtrIcon->setAttribute('src', Page::imagesDir().'/tweeter.png');
        $twtrIcon->setClassName('social-media-icon');
        $twtrLink = new HTMLNode('a');
        $twtrLink->setAttribute('href', 'https://twitter.com/webfiori_');
        $twtrLink->setAttribute('target', '_blank');
        $twtrLink->addChild($twtrIcon);
        $socialMedia->addChild($twtrLink);

        $linkedinIcon = new HTMLNode('img', FALSE);
        $linkedinIcon->setAttribute('src', Page::imagesDir().'/linkedin.png');
        $linkedinIcon->setClassName('social-media-icon');
        $linkedinLink = new HTMLNode('a');
        $linkedinLink->setAttribute('href', 'https://www.linkedin.com/in/ibrahim-binalshikh/');
        $linkedinLink->setAttribute('target', '_blank');
        $linkedinLink->addChild($linkedinIcon);
        $socialMedia->addChild($linkedinLink);

        $bloggerIcon = new HTMLNode('img', FALSE);
        $bloggerIcon->setAttribute('src', Page::imagesDir().'/iconfinder_blogger__social_media_icon_2986189.png');
        $bloggerIcon->setClassName('social-media-icon');
        $bloggerLink = new HTMLNode('a');
        $bloggerLink->setAttribute('href', 'http://ibrahim-2017.blogspot.com');
        $bloggerLink->setAttribute('target', '_blank');
        $bloggerLink->addChild($bloggerIcon);
        $socialMedia->addChild($bloggerLink);
        
        $node->addChild($socialMedia);
        $contactInfo = new HTMLNode();
        $contactInfo->setClassName('pa-'.Page::dir().'-col-12');
        $p = new PNode();
        $p->addText('webfiori@programmingacademia.com',array('new-line'=>TRUE));
        $contactInfo->addChild($p);
        $node->addChild($contactInfo);
        $p->addText('WebFiori Framework, All Rights Reserved © '.date('Y'));
        $div = new HTMLNode('div');
        $div->setAttribute('class', 'pa-ltr-col-twelve');
        $div->addTextNode('<b style="color:gray;font-size:8pt;">Powered By: <a href="https://github.com/usernane/webfiori" '
                . 'target="_blank">WebFiori Framework</a> v'.WebFiori::getConfig()->getVersion().' ('.WebFiori::getConfig()->getVersionType().')</b>',FALSE);
        $node->addChild($div);
        return $node;
    }

    public function getHeadNode() {
        $headTag = new HeadNode();
        $headTag->setBase(WebFiori::getSiteConfig()->getBaseURL());
        $headTag->addLink('icon', Page::imagesDir().'/favicon.png');
        $analId = 'UA-91825602-1';
        $headTag->addJs('https://www.googletagmanager.com/gtag/js?id='.$analId, [
            'async'=>'false'
        ], false);
        $jsCode = new JsCode();
        $jsCode->addCode(''
                . 'window.dataLayer = window.dataLayer || [];'
                . 'function gtag(){'
                . 'dataLayer.push(arguments);'
                . '}'
                . 'gtag(\'js\', new Date());'
                . 'gtag(\'config\', \''.$analId.'\');');
        $headTag->addChild($jsCode);
        $headTag->addCSS(Page::cssDir().'/Grid.css');
        $headTag->addCSS(Page::cssDir().'/colors.css');
        $headTag->addCSS(Page::cssDir().'/theme.css');
        $headTag->addCSS(Page::cssDir().'/api-page.css');
        $headTag->addMeta('robots', 'index, follow');
        return $headTag;
    }

    public function getHeadrNode() {
        $headerSec = new HTMLNode();
        $logoContainer = new HTMLNode();
        $logoContainer->setID('inner-header');
        $logoContainer->setClassName('pa-'.Page::dir().'-col-11-nm-np');
        $img = new HTMLNode('img', FALSE);
        $img->setAttribute('src',Page::imagesDir().'/WebsiteIcon_1024x1024.png');
        $img->setClassName('pa-'.Page::dir().'-col-1-np-nm');
        $img->setID('logo');
        $img->setWritingDir(Page::dir());
        $link = new Anchor(WebFiori::getSiteConfig()->getHomePage(), '');
        $link->addChild($img);
        $headerSec->addChild($link);
        $langCode = 'EN';
        $p = new PNode();
        $siteNames = WebFiori::getSiteConfig()->getWebsiteNames();
        if(isset($siteNames[$langCode])){
            $p->addText('WebFiori Framework APIs', array('bold'=>TRUE));
        }
        else{
            if(isset($_GET['language']) && isset($siteNames[$_GET['language']])){
                $p->addText($siteNames[$_GET['language']], array('bold'=>TRUE));
            }
            else{
                $p->addText('<SITE NAME>', array('bold'=>TRUE));
            }
        }
        $logoContainer->addChild($p);
        $headerSec->addChild($logoContainer);
        //end of logo UI
        //starting of main menu items
        $menu = new HTMLNode('nav');
        $menu->setID('main-nav');
        $menu->setClassName('pa-'.Page::dir().'-col-9');
        $ul = new UnorderedList();
        $ul->setID('main-menu');
        $ul->setClassName('pa-row');
        $ul->setAttribute('dir', Page::dir());
        $menu->addChild($ul);
        $logoContainer->addChild($menu);
        return $headerSec;
    }
    /**
     * 
     * @param AttributeDef $attr
     * @return type
     */
    public function createAttributeDetailsBlock($attr){
        $node = WebFioriAPIGUI::createRowNode(TRUE, FALSE);
        $node->setAttribute('style', 'border: 1px solid;');
        $node->setClassName($node->getAttributeValue('class').' attribute-summary');
        $attrNameNode = WebFioriAPIGUI::createColNode(12, FALSE, FALSE);
        $attrNameNode->setClassName('class-attribute');
        $attrNameNode->setID($attr->getName());
        $nodeText = $attr->getAccessModofier().' '.$attr->getName();
        $attrNameNode->addTextNode($nodeText);
        $node->addChild($attrNameNode);
        $descNode = new HTMLNode();
        $descNode->addTextNode($attr->getDescription(),FALSE);
        $descNode->setClassName('details-box');
        $node->addChild($descNode);
        return $node;
    }

    public function createAttributeSummaryBlock($attr){
        $node = WebFioriAPIGUI::createRowNode(TRUE, FALSE);
        $node->setAttribute('style', 'border: 1px solid;');
        $node->setClassName($node->getAttributeValue('class').' attribute-summary');
        $attrNameNode = WebFioriAPIGUI::createColNode(12, FALSE, FALSE);
        $attrNameNode->setClassName('class-attribute');
        $nodeText = $attr->getAccessModofier().' <a class="class-attribute" href="'.$attr->getPageURL().'/'. str_replace('\\', '/', trim($attr->getOwnerClass()->getNameSpace(), '\\')).'/'.$attr->getOwnerClass()->getName().'#'.$attr->getName().'">'.$attr->getName().'</a>';
        $attrNameNode->addTextNode($nodeText,FALSE);
        $node->addChild($attrNameNode);
        $descNode = new PNode();
        $descNode->addText($attr->getSummary());
        $node->addChild($descNode);
        return $node;
    }
    /**
     * 
     * @return HTMLNode
     */
    public function createClassDescriptionNode(){
        $class = $this->getClass();
        $node = WebFioriAPIGUI::createRowNode();
        $packageNode = new PNode();
        $packageNode->addText('<b class="mono">namespace '.$class->getNameSpace().'</b>',array('esc-entities'=>FALSE));
        $node->addChild($packageNode);
        $titleNode = WebFioriAPIGUI::createTitleNode($class->getAccessModifier().' '.$class->getName());
        $node->addChild($titleNode);
        $descNode = new HTMLNode();
        $descNode->setAttribute('class', 'description-box');
        $descNode->addTextNode($class->getSummary().' '.$class->getDescription(),FALSE);
        $node->addChild($descNode);
        $classV = $class->getVersion();
        if($classV !== NULL){
            $vNode = new PNode();
            $vNode->addText('Version: '.$classV);
            $node->addChild($vNode);
        }
        return $node;
    }
    /**
     * 
     * @param FunctionDef $func
     * @return HTMLNode
     */
    public function createMethodDetailsBlock($func){
        $node = WebFioriAPIGUI::createRowNode(TRUE, FALSE);
        $node->setClassName($node->getAttributeValue('class').' function-details');
        $methNameNode = WebFioriAPIGUI::createColNode(12, FALSE, FALSE);
        $methNameNode->setID(str_replace('&', '', $func->getName()));
        $methNameNode->setClassName($methNameNode->getAttributeValue('class').' function-name');
        $nodeText = $func->getAccessModofier().' '. str_replace('&', '&amp;', $func->getName()).'(';
        $count = count($func->getParameters());
        for($x = 0 ; $x < $count ; $x++){
            $param = $func->getParameters()['param-'.$x];
            if($x + 1 == $count){
                $nodeText .= $param['var-type'].' '. str_replace('&', '&amp', $param['var-name']);
            }
            else{
                $nodeText .= $param['var-type'].' '.str_replace('&', '&amp', $param['var-name']).', ';
            }
        }
        $nodeText .= ')';
        $methNameNode->addTextNode($nodeText,FALSE);
        $node->addChild($methNameNode);
        $descNode = new HTMLNode();
        $descNode->addTextNode($func->getDescription(),FALSE);
        $descNode->setClassName('description-box');
        $node->addChild($descNode);
        if($count != 0){
            $paramsNode = WebFioriGUI::createRowNode(FALSE,FALSE);
            $textNode = new PNode();
            $textNode->addText('Parameters:');
            $paramsNode->addChild($textNode);
            $ul = new UnorderedList();
            $count = count($func->getParameters());
            for($x = 0 ; $x < $count ; $x++){
                $param = $func->getParameters()['param-'.$x];
                $text = '<span style="font-family: monospace;">'.$param['var-type'].' '.$param['var-name'].'</span>';
                if($param['is-optional'] === TRUE){
                    $text .= ' [Optional]';
                }
                $text .= ' '.$param['var-desc'];
                $ul->addListItem($text,FALSE);
            }
            $paramsNode->addChild($ul);
            $node->addChild($paramsNode);
        }
        $return = $func->getMethodReturnTypesStr();
        if($return !== NULL){
            $retNode = WebFioriGUI::createRowNode(FALSE,FALSE);
            $textNode = new PNode();
            $textNode->addText('Returns: <span class="mono">'.$return.'</span>',array('esc-entities'=>FALSE));
            $retNode->addChild($textNode);
            $descNode = new HTMLNode();
            $descNode->addTextNode($func->getMethodReturnDescription(),FALSE);
            $descNode->setClassName('details-box');
            $retNode->addChild($descNode);
            $node->addChild($retNode);
        }
        return $node;
    }
    /**
     * 
     * @param FunctionDef $func
     * @return type
     */
    public function createMethodSummaryBlock($func){
        $node = WebFioriAPIGUI::createRowNode(TRUE, FALSE);
        $node->setAttribute('style', 'border: 1px solid;');
        $node->setClassName($node->getAttributeValue('class').' function-summary');
        $methNameNode = WebFioriAPIGUI::createColNode(12, FALSE, FALSE);
        $methNameNode->setClassName('function-name');
        $nodeText = $func->getAccessModofier().' <a class="function-name" href="'.$func->getPageURL().'/'. str_replace('\\', '/', trim($func->getOwnerClass()->getNameSpace(), '\\')).'/'.$func->getOwnerClass()->getName().'#'.str_replace('&', '', $func->getName()).'">'. str_replace('&', '&amp;', $func->getName()).'</a>(';
        $count = count($func->getParameters());
        for($x = 0 ; $x < $count ; $x++){
            $param = $func->getParameters()['param-'.$x];
            if($x + 1 == $count){
                $nodeText .= $param['var-type'].' '.$param['var-name'];
            }
            else{
                $nodeText .= $param['var-type'].' '.$param['var-name'].', ';
            }
        }
        $nodeText .= ')';
        $methNameNode->addTextNode($nodeText,FALSE);
        $node->addChild($methNameNode);
        $descNode = new PNode();
        $descNode->addText($func->getSummary(),FALSE);
        $node->addChild($descNode);
        return $node;
    }
    /**
     * Creates HTMLNode object that contains namespace index file content.
     * @param NameSpaceAPI $nsObj An object of type NameSpaceAPI.
     * @return HTMLNode The function must be implemented in a way that it returns 
     * an object of type HTMLNode which represents namespace index file content.
     */
    public function createNamespaceContentBlock($nsObj) {
        if($nsObj instanceof NameSpaceAPI){
            $node = WebFioriAPIGUI::createRowNode();
            $titleNode = WebFioriAPIGUI::createTitleNode('Namespace <span class="mono">'.$nsObj->getName().'</span>');
            Page::insert($titleNode);
            $nsArr = $nsObj->getSubNamespaces();
            if(count($nsArr)!=0){
                $nsNode = WebFioriAPIGUI::createRowNode();
                $nsNode->setClassName('classes-container '.$nsNode->getAttributeValue('class'));
                $label = new Label('All Sub-namespaces:');
                $label->setClassName('pa-ltr-col-10 box-title');
                $nsNode->addChild($label);
                foreach ($nsArr as $nsName){
                    $cNode = WebFioriAPIGUI::createRowNode(FALSE, FALSE);
                    $cNode->setClassName('class-row '.$cNode->getAttributeValue('class'));
                    $link = new Anchor($this->getBaseURL(). str_replace('\\', '/', $nsName), $nsName);
                    $cNode->addChild($link);
                    $nsNode->addChild($cNode);
                }
                $node->addChild($nsNode);
            }
            $interfaces = $nsObj->getInterfaces();
            if(count($interfaces) != 0){
                $interfacesNode = WebFioriAPIGUI::createRowNode();
                $interfacesNode->setClassName('classes-container '.$interfacesNode->getAttributeValue('class'));
                $label = new Label('All Interfaces:');
                $label->setClassName('pa-ltr-col-10 box-title');
                $interfacesNode->addChild($label);
                foreach ($interfaces as $interface){
                    $cNode = WebFioriAPIGUI::createRowNode(FALSE, FALSE);
                    $cNode->setClassName('class-row '.$cNode->getAttributeValue('class'));
                    $link = new Anchor($this->getBaseURL(). str_replace('\\', '/', trim($nsObj->getName(),'\\')).'/'.$interface->getName(), $interface->getName());
                    $cNode->addChild($link);
                    $descNode = new PNode();
                    $descNode->addText($interface->getSummary());
                    $cNode->addChild($descNode);
                    $interfacesNode->addChild($cNode);
                }
                $node->addChild($interfacesNode);
            }
            $classes = $nsObj->getClasses();
            if(count($classes) != 0){
                $classesNode = WebFioriAPIGUI::createRowNode();
                $classesNode->setClassName('classes-container '.$classesNode->getAttributeValue('class'));
                $label = new Label('All Classes:');
                $label->setClassName('pa-ltr-col-10 box-title');
                $classesNode->addChild($label);
                foreach ($classes as $class){
                    $cNode = WebFioriAPIGUI::createRowNode(FALSE, FALSE);
                    $cNode->setClassName('class-row '.$cNode->getAttributeValue('class'));
                    $link = new Anchor($this->getBaseURL().str_replace('\\', '/', trim($nsObj->getName(),'\\')).'/'.$class->getName(), $class->getName());
                    $cNode->addChild($link);
                    $descNode = new PNode();
                    $descNode->addText($class->getSummary(),FALSE);
                    $cNode->addChild($descNode);
                    $classesNode->addChild($cNode);
                }
                $node->addChild($classesNode);
            }
            return $node;
        }
    }
    public function createHTMLNode($options = array()) {
        $node = new HTMLNode();
        return $node;
    }

    public function createNSAside($links) {
        
    }

}

