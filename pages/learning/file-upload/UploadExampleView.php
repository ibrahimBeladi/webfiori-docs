<?php
namespace webfiori\views\learn\fileUpload;
use webfiori\entity\Page;
use phpStructs\html\UnorderedList;
use phpStructs\html\CodeSnippet;
/**
 * Description of UploadExampleView
 *
 * @author Ibrahim
 */
class UploadExampleView extends UploadLearnView{
    public function __construct() {
        parent::__construct([
            'title'=>'Usage Example',
            'description'=>'The class \'Uploader\' is a utility class which is '
            . 'used to handle file uploads in PHP in a very simple way.',
            'active-aside'=>3,
        ]);
        Page::document()->getHeadNode()->addCSS('themes/webfiori/css/code-theme.css');
        $this->setPrevTopicLink('learn/topics/file-upload/class-Uploader', 'The Class \'Uploader\'');
        Page::insert($this->createParagraph(''
                . 'Here you will find multiple examples for diffrent use cases at which '
                . 'the class \'Uploader\' can be used in. Usually the most common use case is '
                . 'to upload a file to the server. This use case usually has the following '
                . 'steps:'
                . ''
                . ''));
        $topUl = new UnorderedList();
        $topUl->addListItems([
            'In the back-end:',
            'In the front-end:'
        ]);
        Page::insert($topUl);
        $ul00 = new UnorderedList();
        $topUl->getChild(0)->addChild($ul00);
        $ul00->addListItems([
            'Create an API for uploading files.',
            'In the API, create new instance of the class \'Uploader\'.',
            'Specify upload directory and allowed file types.',
            'Specify the name of the input element that represents file upload input.',
            'Call the method \'<a href="docs/webfiori/entity/Uploader#upload">Uploader::upload()</a>\' to initiate upload process.',
            'Return back a JSON response that shows upload status of the file or files.'
        ],false);
        $ul01 = new UnorderedList();
        $topUl->getChild(1)->addChild($ul01);
        $ul01->addListItems([
            'Create a new form.',
            'Set the attribute \'action\' of the form to be the URL of the API.',
            'Sets the attribute \'method\' of the form to \'post\'.',
            'Sets the attribute \'enctype\' of the form to \'multipart/form-data\'',
            'Add file input to the form and specify the attribute \'name\' of the input.',
            'Add submit input to the form.'
        ]);
        $sec00 = $this->createSection('Uploading One File',4);
        $sec00->addChild($this->createParagraph(''
                . 'Partial Backend Code (Shows Uploader initialization only):'
                . ''));
        $code00 = new CodeSnippet();
        $code00->getCodeElement()->setClassName('language-php');
        $code00->setTitle('PHP Code (Back end)');
        $sec00->addChild($code00);
        $code00->addCodeLine('//...');
        $code00->addCodeLine('$uploader = new Uploader();');
        $code00->addCodeLine('//sets upload path');
        $code00->addCodeLine('$uploader->setUploadDir(\'\home\files\sys-uploads\');');
        $code00->addCodeLine('//allow pdf and word files');
        $code00->addCodeLine('$uploader->addExts([\'doc\',\'docx\',\'pdf\']);');
        $code00->addCodeLine('//sets the name of the location where files are kept in front-end');
        $code00->addCodeLine('$uploader->setAssociatedFileName(\'files-input\');');
        $code00->addCodeLine('//start upload process. Replace if already uploaded.');
        $code00->addCodeLine('$uploader->upload(true);');
        $code00->addCodeLine('//shows a JSON string that shows upload status.');
        $code00->addCodeLine('echo $uploader;');
        $sec00->addChild($this->createParagraph(''
                . 'Assuming that we have created a route to our upload API and '
                . 'the route is "apis/upload-files", The frond end code would '
                . 'be like the following:'
                . ''));
        $code01 = new CodeSnippet();
        $code01->getCodeElement()->setClassName('language-php');
        $code01->setTitle('PHP Code (Front End)');
        $sec00->addChild($code01);
        $code01->setCode(""
                . "use webfiori\entity\Page;\n"
                . "use use phpStructs\html\HTMLNode;\n"
                . "use phpStructs\html\Input;\n"
                . "\n"
                . "class UploadView{\n"
                . "    __construct(){\n"
                . "        \$form = new HTMLNode();\n"
                . "        Page::insert(\$form);\n"
                . "        //assume that upload API exist in the URL apis\upload-files\n"
                . "        \$form->setAttributes([\n"
                . "            'method'=>'post'\n"
                . "            'action'=>'apis/upload-files'\n"
                . "            'enctype'=>'multipart/form-data'\n"
                . "        ]);\n"
                . "        "
                . "        \$filesInput = new Input('file');\n"
                . "        \$form->addChild(\$filesInput);\n"
                . "        //The attribute name must be set to the value which\n"
                . "        // is passed to the method Uploader::setAssociatedFileName()\n"
                . "        \$file->setName('files-input')\n"
                . "        \n"
                . "        \$submit = new Input('submit');\n"
                . "        \$form->addChild(\$submit);\n"
                . "        \n"
                . "        Page::render();\n"
                . "    }\n"
                . "}\n"
                . "return __NAMESPACE__\n"
                . "");
        Page::insert($sec00);
        $sec01 = $this->createSection('Uploading Multiple Files',4);
        $sec01->addChild($this->createParagraph(''
                . 'The code which is used to upload multiple files at one call '
                . 'is the same as the one which is used to upload one file. The '
                . 'diffrence is that we have to set the attribute \'multiple\' of '
                . 'the file input element and set the name to a syntax which '
                . 'looks like an array as following:'
                . ''
                . ''));
        $code02 = new CodeSnippet();
        $code02->setTitle('PHP Code');
        $code02->getCodeElement()->setClassName('language-php');
        $sec01->addChild($code02);
        $code02->setCode(""
                . "\$file->setAttribute('multiple')\n"
                . "\$file->setName('files-input[]')\n"
                . "");
        Page::insert($sec01);
        //$sec02 = $this->createSection('Get MIME Type of File Given its Extension',4);
        //Page::insert($sec02);
        $this->displayView();
    }
}
return __NAMESPACE__;