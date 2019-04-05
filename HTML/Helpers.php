<?php

namespace HTML {

    class Helpers
    {
        static function CreateLinkElement($rel, $href, $integrity = null, $crossorigin = null)
        {
            $attributes = array(
                "rel" => $rel,
                "href" => $href
            );
            if ($integrity !== null) $attributes["integrity"] = $integrity;
            if ($crossorigin !== null) $attributes["crossorigin"] = $crossorigin;
            return new Element('link', $attributes);
        }

        static function CreateStylesheetElement($href, $integrity = null, $crossorigin = null)
        {
            return self::CreateLinkElement("stylesheet", $href, $integrity, $crossorigin);
        }
    }


}