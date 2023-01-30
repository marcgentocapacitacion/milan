<?php

namespace Wagento\ImportData\Model\ProductDescription\Tabs;

/**
 * Class AbstractTabs
 */
class AbstractTabs
{
    /**
     * @param array $row
     *
     * @return string
     */
    protected function getTwoColumns(array $row): string
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
        $html .= '<div  class="pagebuilder-column"
                        data-content-type="column"
                        data-appearance="full-height"
                        data-background-images="{}"
                        data-element="main"
                        data-pb-style="TYH99RM">';
        $html .= $this->getField($row);
        $html .= '</div>';
        $html .= '<div  class="pagebuilder-column"
                        data-content-type="column"
                        data-appearance="full-height"
                        data-background-images="{}"
                        data-element="main"
                        data-pb-style="X6X8P03">';
        $html .= $this->getField($row);
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * @param array $row
     *
     * @return string
     */
    public function getOneColumn(array $row): string
    {
        $html = '<div   class="pagebuilder-column-group"
                        data-background-images="{}"
                        data-content-type="column-group"
                        data-appearance="default"
                        data-grid-size="12"
                        data-element="main"
                        data-pb-style="AP3X06Y">';
        $html .= '<div  class="pagebuilder-column-line"
                        data-content-type="column-line"
                        data-element="main"
                        data-pb-style="JLXF9GK">';
        $html .= '<div  class="pagebuilder-column"
                        data-content-type="column"
                        data-appearance="align-center"
                        data-background-images="{}"
                        data-element="main"
                        data-pb-style="ONT9N6R">';
        $html .= $this->getField($row);
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * @param array $row
     *
     * @return string
     */
    public function getField(array $row): string
    {
        return match ($row['type']) {
            'image' => $this->getImage($row['data1'] ?? ''),
            'text' => $this->getText($row['data1'] ?? ''),
            'link' => $this->getALink($row['data1'] ?? 'Click here', $row['data2'] ?? ''),
            'video_youtube' => $this->getVideoYoutube($row['data1'] ?? ''),
            default => $row['data1'] ?? ''
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
        return '<a tabindex="0"
                   href="' . $url . '"
                   target="_blank"
                   rel="noopener">
                   <span style="color: #236fa1;"><strong>' . $label . '</strong></span>
               </a>';
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
        return '<div data-content-type="text"
                    data-appearance="default"
                    data-element="main"
                    data-pb-style="F4Q0BIE">
                    <p class="MsoNormal" style="text-align: justify;">' . $text . '</p>
                </div>';
    }

    /**
     * @return string
     */
    public function getStyle(): string
    {
        return "
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
        #html-body [data-pb-style=TYH99RM] {
            justify-content: flex-start;
            display: flex;
            flex-direction: column;
            background-position: left top;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: scroll;
            width: 50%;
            align-self: stretch
        }
        #html-body [data-pb-style=X6X8P03] {
            justify-content: center;
            display: flex;
            flex-direction: column;
            background-position: left top;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: scroll;
            width: calc(50% - 20px);
            margin-left: 10px;
            margin-right: 10px;
            align-self: stretch
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
        }
        #html-body [data-pb-style=AP3X06Y] {
            background-position: left top;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: scroll;
            align-self: stretch
        }
        #html-body [data-pb-style=JLXF9GK] {
            display: flex;
            width: 100%
        }
        #html-body [data-pb-style=ONT9N6R] {
            justify-content: center;
            display: flex;
            flex-direction: column;
            background-position: left top;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: scroll;
            width: 100%;
            margin-left: 10px;
            margin-right: 10px;
            align-self: center
        }
        ";
    }
}
