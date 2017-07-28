<?php

class XMLView extends ApiView {
    public function render($content) {
        header('Content-Type: application/xml; charset=utf8');
        echo $content;
        return true;
    }
}
