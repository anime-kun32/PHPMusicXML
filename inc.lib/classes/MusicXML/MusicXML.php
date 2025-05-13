<?php

namespace MusicXML;

use DOMDocument;
use MusicXML\Map\ModelMap;
use MusicXML\Map\NodeType;

class MusicXML extends MusicXMLBase
{
    /**
     * @var mixed Holds the loaded MusicXML object.
     */
    private $musicObject;

    /**
     * Loads a MusicXML file and creates the corresponding object.
     *
     * @param string $path Path to the XML file.
     * @return void
     */
    public function loadXml($path)
    {
        $domdoc = new DOMDocument();
        $domdoc->loadXML(file_get_contents($path));
        $nodes = $domdoc->childNodes;

        foreach ($nodes as $node) {
            if ($node->nodeType == NodeType::ELEMENT && isset(ModelMap::CLASS_MAP[$node->tagName])) {
                $className = ModelMap::CLASS_MAP[$node->tagName];
                $this->musicObject = new $className($node);
                break;
            }
        }

        // Optionally: check if loaded successfully
        if (!$this->musicObject) {
            throw new \Exception("Failed to parse MusicXML file or unsupported root tag.");
        }
    }

    /**
     * Returns the loaded MusicXML object.
     *
     * @return mixed
     */
    public function getMusicXml()
    {
        return $this->musicObject;
    }

    /**
     * Optionally: Converts the object back to XML string if the object supports it.
     *
     * @return string|null
     */
    public function exportXml()
    {
        if (method_exists($this->musicObject, 'toXml')) {
            return $this->musicObject->toXml();
        }

        return null;
    }
}
