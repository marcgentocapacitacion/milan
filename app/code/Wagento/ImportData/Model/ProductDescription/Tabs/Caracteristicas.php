<?php

namespace Wagento\ImportData\Model\ProductDescription\Tabs;

/**
 * Class Caracteristicas
 */
class Caracteristicas extends AbstractTabs implements \Wagento\ImportData\Api\TabsInterface
{
    /**
     * @var string
     */
    private string $idTab = 'V4UDEJV';

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
                    data-tab-name="Características"
                    data-background-images="{}"
                    data-element="main"
                    id="' . $this->idTab . '"
                    data-pb-style="QXMXQ45">
                    <div data-content-type="html" data-appearance="default" data-element="main" data-pb-style="X6VXB0I">';
        $html .= '{{block class="Wagento\ImportData\Block\Product\Characteristic"}} ';
        $html .= '</div>';
        $html .= '</div>';
        $this->setStyle('
        #html-body [data-pb-style=QXMXQ45] {
            justify-content: flex-start;
            display: flex;
            flex-direction: column;
            background-position: left top;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: scroll
        }
        #html-body [data-pb-style=X6VXB0I] {
            margin-left: 15px;
            margin-right: 15px
        }
        td:first-child {
            max-width: none;
            font-weight: bold;
            text-align: left;
        }
        tr td {
            text-align: center;
            border-bottom: 1px solid;
        }
        tr:nth-child(even) {
            background-color: #eeeded;
        }
        table {
            font-size: 14px;
        }');
        return $html;
    }
}
