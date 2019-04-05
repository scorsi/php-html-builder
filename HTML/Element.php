<?php

namespace HTML {

    use Error;

    interface IElement
    {
        public function addChild(Base $element): IElement;

        static public function Div(?array $attributes, ?array $children): IElement;

        static public function H1(?array $attributes, ?array $children): IElement;

        static public function H2(?array $attributes, ?array $children): IElement;

        static public function H3(?array $attributes, ?array $children): IElement;

        static public function H4(?array $attributes, ?array $children): IElement;

        static public function H5(?array $attributes, ?array $children): IElement;

        static public function H6(?array $attributes, ?array $children): IElement;

        static public function Button(?array $attributes, ?array $children): IElement;
    }

    class Element implements Base, IElement
    {
        private $name;
        private $attributes;
        private $children;

        private static $MIN_ATTRS = array("checked", "disabled", "readonly", "multiple",
            "required", "autofocus", "novalidate", "formnovalidate", "selected");
        private static $MIN_ELEMS = array("link", "br", "input");

        function __construct($name, $attributes = null, $children = null)
        {
            $this->name = $name;
            $this->attributes = $attributes;
            $this->children = $children;
        }

        function addChild(Base $element): IElement
        {
            if ($this->children === null)
                $this->children = array();
            $this->children[] = $element;
            return $this;
        }

        function build()
        {
            if (in_array($this->name, self::$MIN_ELEMS)) {
                return "<$this->name" . $this->buildAttributes() . "/>";
            } else {
                return "<$this->name" . $this->buildAttributes() . ">" . $this->buildChildren() . "</$this->name>";
            }
        }

        private function buildChildren()
        {
            if ($this->children === null) return "";
            $str = "";
            foreach ($this->children as $child) {
                $str .= $child->build();
            }
            return $str;
        }

        private function buildAttributes()
        {
            if ($this->attributes === null) return "";
            $str = " ";
            foreach ($this->attributes as $key => $val) {
                if (in_array($key, self::$MIN_ATTRS)) {
                    if (!empty($val)) {
                        $str .= " $key=\"$key\"";
                    }
                } else {
                    $str .= " $key=\"$val\"";
                }
            }
            return $str;
        }

        static public function Div(?array $attributes, ?array $children): IElement
        {
            return new Element('div', $attributes, $children);
        }

        static public function H1(?array $attributes, ?array $children): IElement
        {
            return new Element('h1', $attributes, $children);
        }

        static public function H2(?array $attributes, ?array $children): IElement
        {
            return new Element('h2', $attributes, $children);
        }

        static public function H3(?array $attributes, ?array $children): IElement
        {
            return new Element('h3', $attributes, $children);
        }

        static public function H4(?array $attributes, ?array $children): IElement
        {
            return new Element('h4', $attributes, $children);
        }

        static public function H5(?array $attributes, ?array $children): IElement
        {
            return new Element('h5', $attributes, $children);
        }

        static public function H6(?array $attributes, ?array $children): IElement
        {
            return new Element('h6', $attributes, $children);
        }

        static public function Button(?array $attributes, ?array $children): IElement
        {
            return new Element('button', $attributes, $children);
        }
    }
}
