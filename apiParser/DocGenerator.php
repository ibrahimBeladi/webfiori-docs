<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace webfiori\apiParser;
use webfiori\apiParser\ClassAPI;
use webfiori\entity\Util;
use webfiori\entity\Page;
use phpStructs\Stack;
use phpStructs\html\HTMLNode;
use phpStructs\html\UnorderedList;
use phpStructs\html\ListItem;
use Exception;
/**
 * Description of DocGenerator
 *
 * @author Ibrahim
 */
class DocGenerator {
    private $linksArr;
    private $classesLinksByNS;
    private $apiReadersArr;
    private $baseUrl;
    /**
     * 
     * @param type $options An array of options. The available options are:
     * <ul>
     * <li>path: The location at which the classes are exist.</li>
     * <li>output-to: The location at which the generated HTML files will 
     * be stored to.</li>
     * <li>base-url: A URL that will be used for the tag 'base' in the generated 
     * HTML File.</li>
     * <li>site-name: A name that will be used in the tag 'title'.</li>
     * <li>theme: A theme that can be used to customize the UI of generated 
     * HTML files.</li>
     * <li>inc-private-attrs: A boolean variable. Set to TRUE in order to include 
     * private attributes. Default is FALSE.</li>
     * <li>inc-private-funcs: A boolean variable. Set to TRUE in order to include 
     * private functions. Default is FALSE.</li>
     * * <li>inc-protected-attrs: A boolean variable. Set to TRUE in order to include 
     * protected attributes. Default is TRUE.</li>
     * <li>inc-private-funcs: A boolean variable. Set to TRUE in order to include 
     * protected functions. Default is TRUE.</li>
     * <li></li>
     * <li></li>
     * </ul>
     * @throws Exception
     */
    public function __construct($options=array()) {
        if(isset($options['path'])){
            $options['path'] = str_replace('/', '\\', $options['path']);
            $this->baseUrl = isset($options['base-url']) ? $options['base-url']:'';
            if(Util::isDirectory($options['path'])){
                if(Util::isDirectory($options['output-to'])){
                    $classes = $this->_scanPathForFiles($options['path']);
                    $this->linksArr = array();
                    $this->classesLinksByNS = array();
                    $this->apiReadersArr = array();
                    foreach ($classes as $classPath){
                        $this->apiReadersArr[] = new APIReader($classPath);
                    }
                    $this->_buildLinks();
                    $siteName = isset($options['site-name']) && strlen($options['site-name']) > 0 ?
                            $options['site-name'] : 'Docs';

                    foreach ($this->apiReadersArr as $reader){
                        Page::theme($options['theme']);
                        Page::siteName($siteName);
                        $classAPI = new ClassAPI($reader,$this->linksArr,$options);
                        $classAPI->setBaseURL($this->baseUrl);
                        $page = new APIPage($classAPI);
                        $this->_createAsideNav();
                        $page->createHTMLFile($options['output-to']);
                        Page::reset();
                    }
                }
                else{
                    throw new Exception('Given output path is invalid.');
                }
            }
            else{
                throw new Exception('Given classes path is invalid.');
            }
        }
        else{
            throw new Exception('Classes path is not set.');
        }
    }
    private function _buildLinks() {
        foreach ($this->apiReadersArr as $apiReader){
            $namespaceLink = $apiReader->getNamespace();
            $packageLink2 = str_replace('.', '/', $namespaceLink);
            $cName = $apiReader->getClassName();
            if($packageLink2 === ''){
                $classLink = $this->baseUrl.'/'.$cName;
            }
            else{
                $classLink = $this->baseUrl.$packageLink2.'/'.$cName;
            }
            $nsName = $apiReader->getNamespace();
            $this->linksArr[$cName] = '<a class="mono" href="'.$classLink.'" target="_blank">'.$cName.'</a>';
            $this->classesLinksByNS[$nsName][] = '<a class="side-link" href="'.$classLink.'" target="_blank">'.$cName.'</a>';
            foreach ($apiReader->getConstantsNames() as $name){
                $this->linksArr[$cName.'::'.$name] = '<a class="mono" href="'.$classLink.'#'.$name.'" target="_blank">'.$cName.'::'.$name.'</a>';
            }
            foreach ($apiReader->getFunctionsNames() as $name){
                $this->linksArr[$cName.'::'.$name.'()'] = '<a class="mono" href="'.$classLink.'#'.$name.'" target="_blank">'.$cName.'::'.$name.'()</a>';
            }
        }
    }
    /**
     * Creates aside navigation menu which contains 
     * all system classes along packages.
     */
    private function _createAsideNav(){
        $aside = &Page::document()->getChildByID('side-content-area');
        $aside->addTextNode('<p class="all-classes-label">All Classes:</p>');
        $nav = new HTMLNode('nav');
        $ul = new UnorderedList();
        $ul->setClassName('side-ul');
        $nav->addChild($ul);
        $aside->addChild($nav);
        foreach ($this->classesLinksByNS as $nsName => $nsClasses){
            $packageLi = new ListItem();
            $packageLi->setText($nsName);
            $packageUl = new UnorderedList();
            $packageLi->addChild($packageUl);
            foreach ($nsClasses as $classLink){
                $li = new ListItem();
                $li->addTextNode($classLink);
                $packageUl->addChild($li);
            }
            $ul->addChild($packageLi);
        }
    }
    private function _scanPathForFiles($root){
        $dirsStack = new Stack();
        $dirsStack->push($root);
        $retVal = array();
        while($root = $dirsStack->pop()){
            $subDirs = scandir($root);
            foreach ($subDirs as $subDir){
                if($subDir != '.' && $subDir != '..'){
                    $xSubDir = $root.'\\'.$subDir;
                    if(Util::isDirectory($xSubDir)){
                        $dirsStack->push($xSubDir);
                    }
                    else{
                        if(strpos($subDir, '.php') !== FALSE){
                            $retVal[] = $xSubDir;
                        }
                    }
                }
            }
        }
        return $retVal;
    }
}
