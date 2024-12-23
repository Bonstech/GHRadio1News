<?php
function fetchNews($url, $containerSelector, $titleSelector, $imageSelector, $linkSelector) {
    $news = [];
    $html = file_get_contents($url);

    if ($html) {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        // Parse the base URL
        $baseUrl = parse_url($url);
        $base = $baseUrl['scheme'] . '://' . $parsedUrl['host'];

        // Query all containers
        $containers = $xpath->query($containerSelector);

        foreach ($containers as $container) {
            // Fetch the title, image, and link within each container
            $titleNode = $xpath->query($titleSelector, $container);
            $imageNode = $xpath->query($imageSelector, $container);
            $linkNode = $xpath->query($linkSelector, $container);

            if ($titleNode->length > 0 && $imageNode->length > 0 && $linkNode->length > 0) {

                $rawLink = $linkNode->item(0)->getAttribute('href');

                // Ensure the link is absolute
                if (strpos($rawLink, 'http') !== 0) {
                    $rawLink = $baseUrl . '/' . ltrim($rawLink, '/');
                }

                $news[] = [
                    'title' => trim($titleNode->item(0)->nodeValue),
                    'image' => $imageNode->item(0)->getAttribute('src'),
                    'link' => $rawLink,
                ];
            }

            // Break after retrieving the first four trending news
            if (count($news) >= 4) {
                break;
            }
        }
    }
    return $news;
}

$newsSources = [
    [
        'url' => 'https://www.modernghana.com/ghanahome/news/',
        'containerSelector' => '//div[contains(@class, "col-xs-12 col-sm-9 arcv-nws-center")]/div',
        'titleSelector' => './/h3',
        'imageSelector' => './/img',
        'linkSelector' => './/a'
    ],
    [
        'url' => 'https://citinewsroom.com/news/',
        'containerSelector' => '//div[contains(@class, "jeg_posts jeg_load_more_flag")]/article',
        'titleSelector' => './/h3',
        'imageSelector' => './/img',
        'linkSelector' => './/a'
    ],
    [
        'url' => 'https://www.ghanaweb.com/GhanaHomePage/NewsArchive/',
        'containerSelector' => '//ul[contains(@class, "inner-lead-story-bottom")]/li',
        'titleSelector' => './/a',
        'imageSelector' => './/img',
        'linkSelector' => './/a'
    ],
    
    [
        'url' => 'https://www.myjoyonline.com/news/national/',
        'containerSelector' => '//div[contains(@class, "col-sm-12 col-md-12 col-lg-9 mt-lg-3 mt-sm-3 mt-md-3")]/div',
        'titleSelector' => './/h4',
        'imageSelector' => './/img',
        'linkSelector' => './/a'
    ],
    // Add more sources here
];

$allNews = [];
foreach ($newsSources as $source) {
    $allNews = array_merge($allNews, fetchNews(
        $source['url'],
        $source['containerSelector'],
        $source['titleSelector'],
        $source['imageSelector'],
        $source['linkSelector']
    ));
}

// Save news data to a JSON file
file_put_contents('news.json', json_encode($allNews, JSON_PRETTY_PRINT));
?>