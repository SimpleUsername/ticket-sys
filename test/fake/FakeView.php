<?php
/**
 * Created by PhpStorm.
 * User: Ilia
 * Date: 05.08.14
 * Time: 22:37
 */

namespace test\fake;

use application\core\View;

class FakeView extends View{
    private $_lastContentView;
    private $_lastTemplateView;
    private $_lastData;

    public function generate($content_view, $template_view, $data = null)
    {
        $this->_lastContentView = $content_view;
        $this->_lastTemplateView = $content_view;
        $this->_lastData = $data;
    }

    public function getLastContentView()
    {
        return $this->_lastContentView;
    }
    public function getLastData()
    {
        return $this->_lastData;
    }
    public function getLastTemplateView()
    {
        return $this->_lastTemplateView;
    }


} 