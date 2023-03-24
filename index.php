<?php

        require 'vendor/facebook/webdriver/lib/Chrome/ChromeOptions.php';
        use Facebook\WebDriver\Remote\RemoteWebDriver;
        use Facebook\WebDriver\Chrome\ChromeOptions;
        use Facebook\WebDriver\Remote\DesiredCapabilities;
        use Facebook\WebDriver\WebDriverBy;

        $host = 'http://localhost:4444/wd/hub';
        $chromeOptions = new ChromeOptions();
        $chromeOptions->addArguments(['--headless']); // опционально, для запуска без UI
        $capabilities = DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);
        $driver = RemoteWebDriver::create($host, $capabilities);


        $url = 'https://trastik.com';
        $driver->get($url);

        $loginButton = $driver->findElement(WebDriverBy::cssSelector('.btn-blue'));
        $loginButton->click();

        $emailInput = $driver->findElement(WebDriverBy::cssSelector('#auth-email'));
        $passwordInput = $driver->findElement(WebDriverBy::cssSelector('#auth-password'));

        $emailInput->sendKeys('kp3k@protonmail.com');
        $passwordInput->sendKeys('fddf548fjk');
        $passwordInput->submit();

        $liButton = $driver->findElement(WebDriverBy::cssSelector('.btn-blue'));
        $liButton->click();

        function parse($name){
            $html = file_get_contents('https://trastik.com');

            $dom = new DOMDocument();
            $dom->loadHTML($html);

            $table = $dom->getElementById('sitesTable');
            $rows = $table->getElementsByTagName('tr');

            $fp = fopen($name.'.csv', 'w');

            foreach ($rows as $row) {
                $cols = $row->getElementsByTagName('td');
                $data = array();

                foreach ($cols as $col) {
                    $data[] = $col->nodeValue;
                }

                fputcsv($fp, $data);
            }

            fclose($fp);

        }

        function comp($filename){
                        // открываем исходный csv файл для чтения
                        $source = fopen($filename, 'r');

                        // открываем целевой csv файл для записи
                        $target = fopen('target.csv', 'a+');

                        // записываем заголовок целевого csv файла
                        $header = array('ID', 'Домен', 'ИКС', 'Посещаемость', 'MozRank', 'Majestic Citation Flow', 'Majestic Trust Flow', 'Ahrefs UR', 'Ahrefs DR', 'Ahrefs входящих уникальных', 'LinkPad Акцепторы', 'LinkPad Доноры', 'LinkPad Заспамленность', 'Страна', 'Язык', 'Рубрика и Описание', 'Цена');
                        fputcsv($target, $header, ';');

                        // читаем строки из исходного csv файла
                        while ($row = fgetcsv($source, 0, ';')) {
                            // разбиваем столбец MajesticSeo на Majestic Citation Flow и Majestic Trust Flow
                            $majestic = explode(' ', $row[5]);
                            $row[5] = $majestic[0]; // Majestic Citation Flow
                            $row[] = $majestic[1]; // Majestic Trust Flow

                            // разбиваем столбец Ahrefs на Ahrefs UR, Ahrefs DR и Ahrefs входящих уникальных
                            $ahrefs = explode(' ', $row[6]);
                            $row[6] = $ahrefs[0]; // Ahrefs UR
                            $row[] = $ahrefs[1]; // Ahrefs DR
                            $row[] = $ahrefs[2]; // Ahrefs входящих уникальных

                            // разбиваем столбец LinkPad на LinkPad Акцепторы, LinkPad Доноры и LinkPad Заспамленность
                            $linkpad = explode(' ', $row[7]);
                            $row[7] = $linkpad[0]; // LinkPad Акцепторы
                            $row[] = $linkpad[1]; // LinkPad Доноры
                            $row[] = $linkpad[2]; // LinkPad Заспамленность

                            // разбиваем столбец Страна / Язык на Страна и Язык
                            $country = explode('/', $row[8]);
                            $row[8] = trim($country[0]); // Страна
                            $row[] = trim($country[1]); // Язык

                            // записываем пр

            }
        }



        $filter1 = $driver->findElement(WebDriverBy::cssSelector('input[name="foreign"][value="0"]'));//снг
        $filter1->click();
        $filter2 = $driver->findElement(WebDriverBy::cssSelector('input[type=radio][value=link]'));//снг
        $filter2->click();
        $filter3 = $driver->findElement(WebDriverBy::cssSelector('input[type=radio][value=old]'));//снг
        $filter3->click();
        parse('f1');
        comp('f1.csv');



        $filter2 = $driver->findElement(WebDriverBy::cssSelector('input[type=radio][value=article]'));
        $filter2->click();
        $filter3 = $driver->findElement(WebDriverBy::cssSelector('input[type=radio][value=new]'));
        $filter3->click();
        parse('f2');
        comp('f2.csv');

        $filter1 = $driver->findElement(WebDriverBy::cssSelector('input[name="foreign"][value="1"]'));//снг
        $filter1->click();
        $filter2 = $driver->findElement(WebDriverBy::cssSelector('input[type=radio][value=link]'));//снг
        $filter2->click();
        $filter3 = $driver->findElement(WebDriverBy::cssSelector('input[type=radio][value=old]'));//снг
        $filter3->click();
        parse('f3');
        comp('f3.csv');



        $filter2 = $driver->findElement(WebDriverBy::cssSelector('input[type=radio][value=article]'));//снг
        $filter2->click();
        $filter3 = $driver->findElement(WebDriverBy::cssSelector('input[type=radio][value=new]'));//снг
        $filter3->click();
        parse('f4');
        comp('f4.csv');


