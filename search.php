<?php
if (isset($_GET['query'])) {
    $search = $_GET['query'];
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://knigovo.org.ua/search/text=' . urlencode($search));//Встановлює URL-адресу, на яку буде відправлено HTTP-запит
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);//Дозволяє бібліотеці автоматично переходити за редиректами
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//Повертає вміст запиту як результат, а не виводить його прямо на екран
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//Вимикає перевірку вірогідності партнера під час використання SSL

    $result = curl_exec($curl);
    curl_close($curl);
 
    echo curl_error($curl);

    
    preg_match_all('/<img[^>]*\s+data-src="([^"]+)"[^>]*>/', $result, $imgMatches);
    preg_match_all('/<div\s+class="code">(\d+)<\/div>/', $result, $articleMatches);
    preg_match_all('/<div class="name" title="([^"]+)"><a[^>]+>([^<]+)<\/a><\/div>/', $result, $titleMatches);
    preg_match_all('/<div class="price price\d+" data-price="(\d+(\.\d+)?)">(?:<del>(.*?)<\/del>\s*)?<span>([^<]+)<\/span><\/div>/s', $result, $priceMatches);
    
    if (!empty($titleMatches[1])) {
        echo '<div class="result-box">';
    
        foreach ($titleMatches[1] as $index => $title) {
            echo '<div class="find">';
            echo '<h2>' . $title . '</h2>';
    
            // перевіркa перед використанням масивів для уникнення помилок
            if (!empty($imgMatches[1][$index])) {
                echo '<img src="' . $imgMatches[1][$index] . '">';
            }
            
            if (!empty($articleMatches[1][$index])) {
                echo '<p class="article">Артикул: ' . $articleMatches[1][$index] . '</p>';
            }
    
            if (!empty($priceMatches[4][$index])) {
                echo '<p class="price">Ціна: ' . $priceMatches[4][$index] . '</p>';
            }
    
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p class="no-results">Нічого не знайдено</p>';
    }

    echo '</div>';
}
?>
