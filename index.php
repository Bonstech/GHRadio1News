<?php
$allNews = json_decode(file_get_contents('news.json'), true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trending News</title>
</head>
<body>
    <h1>Trending News</h1>
    <div class="news-container">
        <?php foreach ($allNews as $newsItem): ?>
            <div class="news-item">
                <img src="<?php echo htmlspecialchars($newsItem['image']); ?>" alt="News Image" style="width: 100%; height: auto;">
                <h2><?php echo htmlspecialchars($newsItem['title']); ?></h2>
                <form action="readmore.php" method="post">
                    <input type="hidden" name="url" value="<?php echo htmlspecialchars($newsItem['link']); ?>">
                    echo '<a href="' . $newsItem['link'] . '" target="_blank" rel="noopener noreferrer">Read More</a>';
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
