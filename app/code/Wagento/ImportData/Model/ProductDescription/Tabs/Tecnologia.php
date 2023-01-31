<?php

namespace Wagento\ImportData\Model\ProductDescription\Tabs;

use Wagento\ImportData\Api\TabsInterface;

/**
 * Class Tecnologia
 */
class Tecnologia extends AbstractTabs implements TabsInterface
{
    /**
     * @var string
     */
    private string $idTab = 'LBBTNDB';

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
                    data-tab-name="Tecnología"
                    data-background-images="{}"
                    data-element="main"
                    id="' . $this->idTab . '"
                    data-pb-style="GULP6O5">';
        foreach ($description as $line => $row) {
            $html .= $this->getColumns($row);
        }
        $html .= '</div>';
        $this->setStyle('#html-body [data-pb-style=GULP6O5] {
                background-position: left top;
                background-size: cover;
                background-repeat: no-repeat;
                background-attachment: scroll
            }

            #html-body [data-pb-style=GULP6O5] {
                justify-content: flex-start;
                display: flex;
                flex-direction: column
            }');
        return $html;
    }
}
