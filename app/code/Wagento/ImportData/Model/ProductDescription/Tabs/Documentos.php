<?php

namespace Wagento\ImportData\Model\ProductDescription\Tabs;

/**
 * Class Documentos
 */
class Documentos extends AbstractTabs implements \Wagento\ImportData\Api\TabsInterface
{
    /**
     * @var string
     */
    private string $idTab = 'NRDV67W';

    /**
     * @inheritDoc
     */
    public function getHeader(string $label): string
    {
        return '<li role="tab" class="tab-header" data-element="headers" data-pb-style="XEY2FHJ">
                <a href="#' . $this->idTab . '" class="tab-title">
                    <span class="tab-title">' . $label . '</span>
                </a>
            </li>';
    }

    /**
     * @inheritDoc
     */
    public function getBody(array $description): string
    {
        $html = '<div data-content-type="tab-item"
                    data-appearance="default"
                    data-tab-name="Documentos"
                    data-background-images="{}"
                    data-element="main"
                    id="' . $this->idTab . '"
                    data-pb-style="R0N8R2A">';
        $html .= '<div data-content-type="html" data-appearance="default" data-element="main" data-pb-style="MMV54CO">';
        $html .= '{{block class="Wagento\ImportData\Block\Product\Documents"}} ';
        $html .= '</div>';
        $html .= '</div>';
        $this->setStyle('#html-body [data-pb-style=R0N8R2A] {
            justify-content: flex-start;
            display: flex;
            flex-direction: column;
            background-position: left top;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: scroll
        }
        #html-body [data-pb-style=MMV54CO] {
            text-align: center;
            margin: 15px
        }');
        return $html;
    }
}
