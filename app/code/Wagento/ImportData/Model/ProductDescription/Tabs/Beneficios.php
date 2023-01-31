<?php

namespace Wagento\ImportData\Model\ProductDescription\Tabs;

use Wagento\ImportData\Api\TabsInterface;

/**
 * Class Beneficios
 */
class Beneficios extends AbstractTabs implements TabsInterface
{
    /**
     * @var string
     */
    private string $idTab = 'GYI8697';

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
                    data-tab-name="Beneficios"
                    data-background-images="{}"
                    data-element="main"
                    id="' . $this->idTab . '"
                    data-pb-style="DVWU7QI">';
        foreach ($description as $line => $row) {
            $html .= $this->getColumns($row);
        }
        $html .= '</div>';
        $this->setStyle('#html-body [data-pb-style=DVWU7QI] {
                justify-content: flex-start;
                display: flex;
                flex-direction: column;
                background-position: left top;
                background-size: cover;
                background-repeat: no-repeat;
                background-attachment: scroll
            }');
        return $html;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function getText(string $text): string
    {
        $textList = explode("\n", $text);
        $html = '<div data-content-type="text"
                    data-appearance="default"
                    data-element="main"
                    data-pb-style="F4Q0BIE">';
        foreach ($textList ?? [] as $value) {
            $html .= '<p class="MsoNormal" style="text-align: center;"><span style="font-size: 20px;">' . $value . '</span></p>';
        }
        $html .= '</div>';
        return $html;
    }
}
