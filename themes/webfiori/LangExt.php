<?php
use webfiori\entity\Page;
/**
 * Extending language file by adding more labels.
 *
 * @author Ibrahim
 */
class LangExt {
    public static function extLang(){
        $trans = &Page::translation();
        $trans->createDirectory('menus/main-menu');
        $trans->setMultiple('menus/main-menu', array(
            'menu-item-1'=>'Download',
            'menu-item-2'=>'API Docs',
            'menu-item-3'=>'Learnning Center',
            'menu-item-4'=>'Menu Item 4'
        ));
    }
}
