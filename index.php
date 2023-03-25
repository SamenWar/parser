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

        function comp(){
            // собрать все CSV файлы в один массив
            $combinedData = array();
            $dir = "C:\OSPanel\domains\parser";
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        if (pathinfo($file, PATHINFO_EXTENSION) == "csv") {
                            $handle = fopen($dir . "/" . $file, "r");
                            while (($data = fgetcsv($handle, 0, ";")) !== false) {
                                $combinedData[] = $data;
                            }
                            fclose($handle);
                        }
                    }
                    closedir($dh);
                }
            }

            // parse the combined CSV data
            $parsedData = array();
            foreach ($combinedData as $row) {
                $parsedRow = array();
                $parsedRow['ID'] = $row[0];
                $parsedRow['Домен'] = $row[1];
                $parsedRow['ИКС'] = $row[2];
                $parsedRow['Посещаемость'] = $row[3];
                $parsedRow['MozRank'] = $row[4];

                // parse MajesticSeo data
                $majesticSeoData = explode(";", $row[5]);
                $parsedRow['Majestic Citation Flow'] = $majesticSeoData[0];
                $parsedRow['Majestic Trust Flow'] = $majesticSeoData[1];

                // parse Ahrefs data
                $ahrefsData = explode(";", $row[6]);
                $parsedRow['Ahrefs UR'] = $ahrefsData[0];
                $parsedRow['Ahrefs DR'] = $ahrefsData[1];
                $parsedRow['Ahrefs входящих уникальных'] = $ahrefsData[2];

                // parse LinkPad data
                $linkPadData = explode(";", $row[7]);
                $parsedRow['LinkPad Акцепторы'] = $linkPadData[0];
                $parsedRow['LinkPad Доноры'] = $linkPadData[1];
                $parsedRow['LinkPad Заспамленность'] = $linkPadData[2];

                // parse Страна / Язык data
                $countryAndLanguageData = explode(";", $row[8]);
                $parsedRow['Страна'] = $countryAndLanguageData[0];
                $parsedRow['Язык'] = $countryAndLanguageData[1];

                $parsedRow['Рубрика и Описание'] = $row[9];
                $parsedRow['Цена'] = $row[10];

                $parsedData[] = $parsedRow;
            }

            // write the parsed data to a new CSV file
            $outputFile = "C:/OSPanel/domains/parser/file.csv";
            $outputHandle = fopen($outputFile, "w");
            $headerRow = array_keys($parsedData[0]);
            fputcsv($outputHandle, $headerRow, ";");
            foreach ($parsedData as $row) {
                fputcsv($outputHandle, $row, ";");
            }
            fclose($outputHandle);



        }



        $filter1 = $driver->findElement(WebDriverBy::cssSelector('input[name="foreign"][value="0"]'));//снг
        $filter1->click();
        $filter2 = $driver->findElement(WebDriverBy::cssSelector('input[type=radio][value=link]'));//снг
        $filter2->click();
        $filter3 = $driver->findElement(WebDriverBy::cssSelector('input[type=radio][value=old]'));//снг
        $filter3->click();
        parse('f1');



        $filter2 = $driver->findElement(WebDriverBy::cssSelector('input[type=radio][value=article]'));
        $filter2->click();
        $filter3 = $driver->findElement(WebDriverBy::cssSelector('input[type=radio][value=new]'));
        $filter3->click();
        parse('f2');

        $filter1 = $driver->findElement(WebDriverBy::cssSelector('input[name="foreign"][value="1"]'));//снг
        $filter1->click();
        $filter2 = $driver->findElement(WebDriverBy::cssSelector('input[type=radio][value=link]'));//снг
        $filter2->click();
        $filter3 = $driver->findElement(WebDriverBy::cssSelector('input[type=radio][value=old]'));//снг
        $filter3->click();
        parse('f3');



        $filter2 = $driver->findElement(WebDriverBy::cssSelector('input[type=radio][value=article]'));//снг
        $filter2->click();
        $filter3 = $driver->findElement(WebDriverBy::cssSelector('input[type=radio][value=new]'));//снг
        $filter3->click();
        parse('f4');

        comp();


$driver->quit();