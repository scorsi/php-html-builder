<?php

namespace HTML {

    use Error;

    interface IResourceBuilder
    {
        public function addStylesheets(array $links): IResourceBuilder;

        public function addStylesheet(string $link): IResourceBuilder;

        public function addScripts(array $links, bool $addToBody = false): IResourceBuilder;

        public function addScript(string $link, bool $addToBody = false): IResourceBuilder;
    }

    interface IBuilder
    {
        public function getResourceBuilder(): IResourceBuilder;

        public function addStylesheets(array $links): IBuilder;

        public function addStylesheet(string $link): IBuilder;

        public function addScripts(array $links, bool $addToBody = false): IBuilder;

        public function addScript(string $link, bool $addToBody = false): IBuilder;

        public function addElements(array $element): IBuilder;

        public function addElement(Base $element): IBuilder;

        public function build(): string;
    }

    class ResourcesBuilder implements IResourceBuilder
    {
        private $stylesheets = [];
        private $scripts = [
            "head" => [],
            "body" => [],
        ];

        public function addStylesheets(array $links): IResourceBuilder
        {
            foreach ($links as $link) {
                if (!is_string($link)) throw new Error("Stylesheet link must be a string");
                $this->addStylesheet($link);
            }
            return $this;
        }

        public function addStylesheet(string $link): IResourceBuilder
        {
            $this->stylesheets[] = $link;
            return $this;
        }

        public function addScripts(array $links, bool $addToBody = false): IResourceBuilder
        {
            foreach ($links as $link) {
                if (!is_string($link)) throw new Error("Script link must be a string");
                $this->addScript($link, $addToBody);
            }
            return $this;
        }

        public function addScript(string $link, bool $addToBody = false): IResourceBuilder
        {
            if ($addToBody) {
                $this->scripts["body"][] = $link;
            } else {
                $this->scripts["head"][] = $link;
            }
            return $this;
        }

        public function getStylesheets(): array
        {
            return $this->stylesheets;
        }

        public function getScripts(): array
        {
            return $this->scripts;
        }
    }

    class Builder implements IBuilder
    {
        private $resourcesBuilder = null;
        private $children;

        public function __construct()
        {
            $this->children = [
                new Element('head'),
                new Element('body'),
            ];
        }

        public function getResourceBuilder(): IResourceBuilder
        {
            if ($this->resourcesBuilder === null) $this->resourcesBuilder = new ResourcesBuilder();
            return $this->resourcesBuilder;
        }

        private function buildResources(): void
        {
            if ($this->resourcesBuilder === null) return;

            list(&$head, &$body) = $this->children;

            foreach ($this->resourcesBuilder->getStylesheets() as $stylesheetLink) {
                $head->addChild(new Element("link", array("rel" => "stylesheet", "href" => $stylesheetLink)));
            }

            foreach ($this->resourcesBuilder->getScripts()["head"] as $scriptLink) {
                $head->addChild(new Element("script", array("src" => $scriptLink)));
            }

            foreach ($this->resourcesBuilder->getScripts()["body"] as $scriptLink) {
                $body->addChild(new Element("script", array("src" => $scriptLink)));
            }
        }

        public function build(): string
        {
            $this->buildResources();

            list(&$head, &$body) = $this->children;

            return '<html><head>'
                . $head->build()
                . '</head><body>'
                . $body->build()
                . '</body>';
        }

        public function addStylesheets(array $links): IBuilder
        {
            $this->getResourceBuilder()->addStylesheets($links);
            return $this;
        }

        public function addStylesheet(string $link): IBuilder
        {
            $this->getResourceBuilder()->addStylesheet($link);
            return $this;
        }

        public function addScripts(array $links, bool $addToBody = false): IBuilder
        {
            $this->getResourceBuilder()->addScripts($links, $addToBody);
            return $this;
        }

        public function addScript(string $link, bool $addToBody = false): IBuilder
        {
            $this->getResourceBuilder()->addScript($link, $addToBody);
            return $this;
        }

        public function addElements(array $elements): IBuilder
        {
            foreach ($elements as $element) {
                if (!($element instanceof Base)) throw new Error('Element must be of type \HTML\Base, either Element or Fragment');
                $this->addElement($element);
            }
            return $this;
        }

        public function addElement(Base $element): IBuilder
        {
            list(&$head, &$body) = $this->children;
            $body->addChild($element);
            return $this;
        }
    }

}