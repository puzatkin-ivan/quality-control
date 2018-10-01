<?php
require_once __DIR__. '/HtmlParser.class.php';

class Main
{
    const COUNT_ARGUMENTS = 2;
    const DIR_LOG = __DIR__ . '/../log';

    /** @var HtmlParser */
    private $htmlParser;

    /** @var string */
    private $url;

    /** @var array */
    private $urls;

    /**
     * Main constructor.
     * @param int $argc
     * @param array $argv
     */
    public function __construct($argc, $argv)
    {
        if ($argc !== self::COUNT_ARGUMENTS)
        {
            throw new InvalidArgumentException('Invalid arguments.');
        }

        $this->url = $argv[1];

        if (!file_exists(self::DIR_LOG))
        {
            mkdir(self::DIR_LOG);
        }
    }

    /**
     * @throws Exception
     */
    public function run()
    {
        $this->htmlParser = new HtmlParser($this->url);
        $this->urls = [$this->url];
        while (count($this->urls) >= 1)
        {
            $result = [];
            foreach ($this->urls as $url)
            {
                if(!empty($url))
                {
                    $result = array_merge($result, $this->htmlParser->getAllLinksByLink($url));
                }
            }
            $result = array_unique($result);
            $this->urls = array_diff($result, $this->urls);
        }
        $this->log('fail.log', $this->htmlParser->getBadUrls());
        $this->log('success.log', $this->htmlParser->getSuccessUrls());
    }

    /**
     * @param string $filename
     * @param array $data
     */
    private function log(string $filename, array $data)
    {
        $file = fopen(self::DIR_LOG . '/' . $filename, 'w');
        foreach ($data as $item)
        {
            fwrite($file, $item . PHP_EOL);
        }
        fclose($file);
    }
}