<?php

namespace Wagento\ImportData\Model\ProductDescription\Tabs;

use Wagento\ImportData\Api\TabsInterface;

/**
 * Class Geometria
 */
class Geometria extends AbstractTabs implements TabsInterface
{
    /**
     * @var string
     */
    private string $idTab = 'N0LTS12';

    /**
     * @param string $label
     *
     * @return string
     */
    public function getHeader(string $label): string
    {
        return '<li role="tab" class="tab-header" data-element="headers" data-pb-style="T0P7E1D">
                <a href="#' . $this->idTab . '" class="tab-title">
                    <span class="tab-title">' . $label . '</span>
                </a>
            </li>';
    }

    /**
     * @param array $description
     *
     * @return string
     */
    public function getBody(array $description): string
    {
        $html = '<div data-content-type="tab-item"
                    data-appearance="default"
                    data-tab-name="Geometría"
                    data-background-images="{}"
                    data-element="main"
                    id="' . $this->idTab . '"
                    data-pb-style="N2WQJIC">';
        foreach ($description as $line => $row) {
            $html .= $this->getColumns($row);
        }
        $html .= '</div>';
        $this->setStyle('#html-body [data-pb-style=N2WQJIC] {
            background-position: left top;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: scroll
        }

        #html-body [data-pb-style=N2WQJIC] {
            justify-content: flex-start;
            display: flex;
            flex-direction: column
        }');
        return $html;
    }
}
