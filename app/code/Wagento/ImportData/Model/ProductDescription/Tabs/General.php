<?php

namespace Wagento\ImportData\Model\ProductDescription\Tabs;

use Wagento\ImportData\Api\TabsInterface;

/**
 * Class General
 */
class General extends AbstractTabs implements TabsInterface
{
    /**
     * @var string
     */
    private string $idTab = 'Y0EHBR0';

    /**
     * @param string $label
     *
     * @return string
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
     * @param array $description
     *
     * @return string
     */
    public function getBody(array $description): string
    {
        $html = '<div   data-content-type="tab-item"
                        data-appearance="default"
                        data-tab-name="General"
                        data-background-images="{}"
                        data-element="main"
                        id="' . $this->idTab . '"
                        data-pb-style="FHEVQ9P">';
        $firstTime = true;
        foreach ($description as $line => $row) {
            if (!$firstTime) {
                $html .= '<div data-content-type="divider" data-appearance="default" data-element="main">
                            <hr data-element="line" data-pb-style="OJLLULW">
                        </div>';
            }
            $html .= $this->getColumns($row);
            $firstTime = false;
        }
        $html .= '</div>';
        $this->setStyle('#html-body [data-pb-style=XEY2FHJ] {
            border-width: 1px
        }

        #html-body [data-pb-style=FHEVQ9P] {
            justify-content: flex-start;
            display: flex;
            flex-direction: column
        }
        #html-body [data-pb-style=FHEVQ9P] {
            background-position: left top;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: scroll
        }
        #html-body [data-pb-style=OJLLULW] {
            width: 100%;
            border-width: 1px;
            border-color: #cecece;
            display: inline-block
        }');
        return $html;
    }
}
