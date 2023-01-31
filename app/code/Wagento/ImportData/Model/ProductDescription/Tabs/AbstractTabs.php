<?php

namespace Wagento\ImportData\Model\ProductDescription\Tabs;

/**
 * Class AbstractTabs
 */
class AbstractTabs
{
    /**
     * @var array
     */
    protected array $style = [];

    /**
     * AbstractTabs constructor
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * @return $this
     */
    protected function init()
    {
        $this->setStyle("
        #html-body [data-pb-style=KIYPT3C] {
            background-position: left top;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: scroll
        }
        #html-body [data-pb-style=KIYPT3C] {
            text-align: center;
            align-self: stretch
        }
        #html-body [data-pb-style=CGSWTM6] {
            display: flex;
            width: 100%
        }
        #html-body [data-pb-style=WO8QUR7] {
            text-align: center;
            margin: 10px 10px 10px 15px;
            border-style: none
        }
        #html-body [data-pb-style=WO8QUR7] {
            border-style: none
        }
        #html-body [data-pb-style=A1IVKEA] {
            max-width: 100%;
            height: auto
        }
        #html-body [data-pb-style=F4Q0BIE] {
            margin-left: 15px;
            margin-right: 15px
        }

        #html-body [data-pb-style=T81C5D2] {
            max-width: 1000px
        }

        #html-body [data-pb-style=L33T0U4] {
            text-align: center;
            margin-left: 10px;
            margin-right: 10px
        }");
        return $this;
    }

    /**
     * @param array $row
     *
     * @return string
     */
    protected function getColumns(array $row): string
    {
        $html = '<div  class="pagebuilder-column-group"
                       data-background-images="{}"
                       data-content-type="column-group"
                       data-appearance="default"
                       data-grid-size="12"
                       data-element="main"
                       data-pb-style="KIYPT3C">';
        $html .= '<div  class="pagebuilder-column-line"
                        data-content-type="column-line"
                        data-element="main"
                        data-pb-style="CGSWTM6">';
        $qtdColumns = [];
        foreach ($row as $column => $item) {
            if (!isset($qtdColumns[$column])) {
                $qtdColumns[$column] = 1;
            } else {
                $qtdColumns[$column] = $qtdColumns[$column] + 1;
            }
            if ($item['type'] != 'link') {
                continue;
            }

            if (isset($row[$column - 1]) && $row[$column - 1]['type'] == 'text') {
                $row[$column - 1]['data1'] .= $this->getALink(
                    $item['data1'] ?? 'Click here',
                    $item['data2'] ?? ''
                );
                unset($row[$column]);
            }
        }

        foreach ($row as $column => $item) {
            if ($item['type'] == 'video_youtube') {
                return $this->getField($item);
            }
            $dataPbStyle = "X6X8P03".time();
            $html .= '<div  class="pagebuilder-column"
                        data-content-type="column"
                        data-appearance="align-center"
                        data-background-images="{}"
                        data-element="main"
                        data-pb-style="' . $dataPbStyle . '">';
            $html .= $this->getField($item);
            $html .= '</div>';
            $this->setStyle("#html-body [data-pb-style=" . $dataPbStyle . "] {
            justify-content: center;
            display: flex;
            flex-direction: column;
            background-position: left top;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: scroll;
            width: " . (100/$qtdColumns[$column]) . "%;
            margin-left: 10px;
            margin-right: 10px;
            align-self: stretch
        }");
        }
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * @param array $row
     *
     * @return string
     */
    public function getField(array $item): string
    {
        return match ($item['type']) {
            'image' => $this->getImage($item['data1'] ?? ''),
            'text' => $this->getText($item['data1'] ?? ''),
            'link' => $this->getALink($item['data1'] ?? 'Click here', $item['data2'] ?? ''),
            'video_youtube' => $this->getVideoYoutube($item['data1'] ?? ''),
            'table' => $this->getTable($item),
            default => $item['data1'] ?? ''
        };
    }

    /**
     * @param string $url
     *
     * @return string
     */
    public function getVideoYoutube(string $url): string
    {
        return '<div data-content-type="video" data-appearance="default" data-element="main" data-pb-style="L33T0U4">
                <div class="pagebuilder-video-inner" data-element="inner" data-pb-style="T81C5D2">
                    <div class="pagebuilder-video-wrapper" data-element="wrapper">
                        <div class="pagebuilder-video-container">
                            <iframe frameborder="0" allowfullscreen=""
                                    src="' . $url . '"
                                    autoplay="true" muted="true" data-element="video"></iframe>
                        </div>
                    </div>
                </div>
            </div>';
    }

    /**
     * @param string $label
     * @param string $url
     *
     * @return string
     */
    public function getALink(string $label, string $url): string
    {
        return str_replace("\n", '', '<a tabindex="0" href="' . $url . '" target="_blank" rel="noopener">
                   <span style="color: #236fa1;"><strong>' . $label . '</strong></span>
               </a>');
    }

    /**
     * @param string $file
     *
     * @return string
     */
    public function getImage(string $file): string
    {
        return '<figure data-content-type="image"
                        data-appearance="full-width"
                        data-element="main"
                        data-pb-style="WO8QUR7">
                        <img class="pagebuilder-mobile-hidden"
                            src="{{media url=' . $file . '}}"
                            alt=""
                            title=""
                            data-element="desktop_image"
                            data-pb-style="A1IVKEA"/>
                        <img class="pagebuilder-mobile-only"
                            src="{{media url=' . $file . '}}"
                            alt=""
                            title=""
                            data-element="mobile_image"
                            data-pb-style="A1IVKEA"/>
                </figure>';
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
            $html .= '<p class="MsoNormal" style="text-align: justify;">' . $value . '</p>';
        }
        $html .= '</div>';
        return $html;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function getTable(array $data): string
    {
        if (!isset($data['dataColumns'])) {
            return '';
        }

        $html = '<table border="1" cellpadding="1" cellspacing="1">';
        $html .= '<tbody>';
        foreach ($data['dataColumns'] as $dataTable) {
            $html .= '<tr>';
            foreach ($dataTable as $column => $item) {
                if ($column <= 0) {
                    $html .= '<td>' . $item . '</td>';
                } else {
                    $html .= '<td style="text-align:center">' . $item . '</td>';
                }
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        $this->setStyle('td:first-child {
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

    /**
     * @return string
     */
    public function getStyle(): string
    {
        return str_replace("\n", '' , implode('', $this->style));
    }

    /**
     * @param string $style
     *
     * @return void
     */
    public function setStyle(string $style): void
    {
        $this->style[] = $style;
    }
}
