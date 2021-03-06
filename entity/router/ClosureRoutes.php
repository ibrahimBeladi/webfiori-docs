<?php
/*
 * The MIT License
 *
 * Copyright 2019 Ibrahim, WebFiori Framework.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace webfiori\entity\router;
use webfiori\entity\Logger;
use webfiori\apiParser\DocGenerator;
use webfiori\entity\File;
use webfiori\WebFiori;
use webfiori\entity\Page;
/**
 * A class that only has one method to initiate some of system routes.
 * The class is meant to only initiate the routes which uses the method 
 * Router::closure().
 * @author Ibrahim
 * @version 1.0
 */
class ClosureRoutes {
    /**
     * Create all closure routes. Include your own here.
     * @since 1.0
     */
    public static function create() {
        Router::closure([
            'path'=>'/docs',
            'route-to'=>function(){
                header('location: '.WebFiori::getSiteConfig()->getBaseURL().'docs/webfiori');
            }
        ]);
        Router::closure([
            'path'=>'/downloads/webfiori-v1.0.9-stable', 
            'route-to'=>function(){
                $f = new File();
                $f->setName('webfiori-1.0.9-stable.zip');
                $f->setPath(ROOT_DIR.'/res/release');
                $f->view(TRUE);
        }]);
        Router::closure([
            'path'=>'/downloads/webfiori-v1.0.8-stable', 
            'route-to'=>function(){
                $f = new File();
                $f->setName('webfiori-1.0.8-stable.zip');
                $f->setPath(ROOT_DIR.'/res/release');
                $f->view(TRUE);
        }]);
        Router::closure([
            'path'=>'/downloads/webfiori-v1.0.7-stable', 
            'route-to'=>function(){
                $f = new File();
                $f->setName('webfiori-1.0.7-stable.zip');
                $f->setPath(ROOT_DIR.'/res/release');
                $f->view(TRUE);
        }]);
        Router::closure([
            'path'=>'/downloads/webfiori-v1.0.6-stable', 
            'route-to'=>function(){
                $f = new File();
                $f->setName('webfiori-1.0.6-stable.zip');
                $f->setPath(ROOT_DIR.'/res/release');
                $f->view(TRUE);
        }]);
        Router::closure([
            'path'=>'/downloads/webfiori-v1.0.5-stable', 
            'route-to'=>function(){
                $f = new File();
                $f->setName('webfiori-1.0.5-stable.zip');
                $f->setPath(ROOT_DIR.'/res/release');
                $f->view(TRUE);
        }]);
        Router::closure([
            'path'=>'/downloads/webfiori-v1.0.3-stable', 
            'route-to'=>function(){
                $f = new File();
                $f->setName('webfiori-1.0.3-stable.zip');
                $f->setPath(ROOT_DIR.'/res/release');
                $f->view(TRUE);
        }]);
        Router::closure([
            'path'=>'/downloads/webfiori-v1.0.2-stable', 
            'route-to'=>function(){
                $f = new File();
                $f->setName('webfiori-1.0.2-stable.zip');
                $f->setPath(ROOT_DIR.'/res/release');
                $f->view(TRUE);
        }]);
        Router::closure([
            'path'=>'/downloads/webfiori-v1.0.0-beta-1', 
            'route-to'=>function(){
                $f = new File();
                $f->setName('webfiori-1.0.0-beta-1.zip');
                $f->setPath(ROOT_DIR.'/res/release');
                $f->view(TRUE);
        }]);
        Router::closure([
            'path'=>'/downloads/webfiori-v1.0.0-beta-2', 
            'route-to'=>function(){
                $f = new File();
                $f->setName('webfiori-1.0.0-beta-2.zip');
                $f->setPath(ROOT_DIR.'/res/release');
                $f->view(TRUE);
        }]);
        Router::closure([
            'path'=>'/downloads/webfiori-v1.0.2-beta-1', 
            'route-to'=>function(){
                $f = new File();
                $f->setName('webfiori-1.0.2-beta-1.zip');
                $f->setPath(ROOT_DIR.'/res/release');
                $f->view(true);
        }]);
        Router::closure([
            'path'=>'/downloads/webfiori-v1.0.0-stable', 
            'route-to'=>function (){
                $f = new File();
                $f->setName('webfiori-1.0.0-stable.zip');
                $f->setPath(ROOT_DIR.'/res/release');
                $f->view(TRUE);
        }]);
        Router::closure([
            'path'=>'/downloads/webfiori-v1.0.1-stable', 
            'route-to'=>function (){
                $f = new File();
                $f->setName('webfiori-1.0.1-stable.zip');
                $f->setPath(ROOT_DIR.'/res/release');
                $f->view(TRUE);
        }]);
        Router::setOnNotFound(function(){
            new \webfiori\views\NotFound();
        });
        Router::closure([
            'path'=>'x/apis/shields-get-dontate-badget', 
            'route-to'=>function(){
                $j = new \jsonx\JsonX();
                $j->add('label', 'donate');
                $j->add('message', 'PayPal');
                $j->add('color', 'blue');
                $j->add('namedLogo', 'PayPal');
                echo $j;
        }]);
        
    }
}
