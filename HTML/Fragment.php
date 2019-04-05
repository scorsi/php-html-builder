<?php

namespace HTML {

    class Fragment implements Base
    {
        private $text;

        public function __construct($text)
        {
            $this->text = $text;
        }

        public function build()
        {
            return $this->text;
        }
    }

}