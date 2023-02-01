<?php

namespace Wagento\ImportData\Model\ProductDescription;

use Wagento\ImportData\Api\ProductDescriptionInterface;
use Wagento\ImportData\Api\TabsInterface;

/**
 * Class ProductDescription
 */
class ProductDescription implements ProductDescriptionInterface
{
    /**
     * @var array | TabsInterface[]
     */
    protected array $tabsImport;

    /**
     * @var array
     */
    protected array $tabsImportWithoutImport = [
        'Caracteristicas' => true,
        'Documentos' => true
    ];

    /**
     * @param array $tabsImport
     */
    public function __construct(array $tabsImport)
    {
        $this->tabsImport = $tabsImport;
    }

    /**
     * @param array $description
     *
     * @return string
     */
    public function getHtml(array $description): string
    {
        $label = '';
        $body = '';
        $style = '';
        foreach ($this->tabsImport as $name => $tabsImport) {
            if (!($tabsImport instanceof TabsInterface)) {
                continue;
            }

            if (isset($this->tabsImportWithoutImport[$name])) {
                $label .= $tabsImport->getHeader($name);
                $body .= $tabsImport->getBody([]);
                $style .= $tabsImport->getStyle();
                continue;
            }

            if (!isset($description[$name])) {
                continue;
            }
            $label .= $tabsImport->getHeader($name);
            $body .= $tabsImport->getBody($description[$name]);
            $style .= $tabsImport->getStyle();
        }
        $html = '<style>';
        $html .= $style;
        $html .= '#html-body [data-pb-style=CHTQSB1] {text-align: center;}';
        $html .= '#html-body [data-pb-style=VRP6GVW] {border-width: 1px;min-height: 300px;}';
        $html .= '</style>';
        $html .= '<div class="tab-align-center"
                    data-content-type="tabs"
                    data-appearance="default"
                    data-active-tab="0"
                    data-element="main">';
        $html .= '<ul role="tablist" class="tabs-navigation" data-element="navigation" data-pb-style="CHTQSB1">';
        $html .= $label;
        $html .= '</ul>';
        $html .= '<div class="tabs-content" data-element="content" data-pb-style="VRP6GVW">';
        $html .= $body;
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
}
