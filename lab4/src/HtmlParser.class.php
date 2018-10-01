<?php

class HtmlParser
{
    const SCHEMES = ['http', 'https'];

    /** @var string */
    private $url;

    /** @var string */
    private $domain;

    /** @var DOMDocument */
    private $dom;

    /** @var array */
    private $successUrls;

    /** @var array */
    private $badUrls;

    /**
     * HtmlParser constructor.
     * @param string $url
     * @throws Exception
     */
    public function __construct(string $url)
    {
        $this->url = $url;
        $this->domain = $this->getDomain($url);
        if (empty($this->domain))
        {
            throw new Exception('Invalid url.');
        }
        $this->dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $this->badUrls = [];
        $this->successUrls = [];
    }

    /**
     * @return array
     */
    public function getBadUrls()
    {
        return $this->badUrls;
    }

    /**
     * @return array
     */
    public function getSuccessUrls()
    {
        return $this->successUrls;
    }

    /**
     * @param $url
     * @return array
     */
    public function getAllLinksByLink($url)
    {
        if ($this->isNotUrlExists($url))
        {
            $this->badUrls[] = $url;
            return [];
        }
        if (in_array($url, $this->successUrls) || in_array($url, $this->badUrls))
        {
            return [];
        }
        $this->successUrls[] = $url;
        $html = file_get_contents($url);
        $this->dom->loadHTML($html);
        $links = $this->dom->getElementsByTagName('a');

        $result = [];
        foreach ($links as $link)
        {
            /** @var DOMElement $link */
            $href = $this->processLink($link->getAttribute('href'));
            if (isset($href))
            {
                $result[] = $href;
            }
        }
        return $result;
    }

    private function isNotUrlExists($url)
    {
        $file_headers = @get_headers($url);
        return (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found');
    }

    /**
     * @param string $link
     * @return string
     */
    private function processLink($link)
    {
        return ($this->isLinkBelongDomain($link)) ?: null;
    }

    private function isLinkBelongDomain($link)
    {
        $params = parse_url($link);

        if (empty($params))
        {
            return false;
        }

        if (!empty($params['scheme']) && !in_array($params['scheme'], self::SCHEMES))
        {
            return false;
        }

        if (!empty($params['host']) && $params['host'] !== $this->domain)
        {
            return false;
        }

        return $this->generateURL($params);
    }

    /**
     * @param $params
     * @return string
     */
    private function generateURL($params)
    {
        $result = $this->domain;
        $result .= '/' . $params['path'];
        return $result;
    }

    /**
     * @param $link
     * @return mixed|null
     */
    private function getDomain($link)
    {
        $result = parse_url($link);
        if (empty($result['scheme']) && empty($result['host']))
        {
            throw new InvalidArgumentException('Invalid url.');
        }

        return $result['scheme'] . '://' . $result['host'];
    }
}