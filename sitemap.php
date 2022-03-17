<?php

class Sitemap{

    #Метод для выбора функции в зависимости от выбранного расширения
    function create_sitemap(array $datas, string $extension, string $path){
        
        #Создаем директории в соотвествии с указанным путём
        try{
            $this->create_dir($path);
        }
        catch(Exception $e){
            echo 'Ошибка: ',  $e->getMessage(), "\n";
            exit();
        }
        switch ($extension)
        {
            case 'xml' : { $this->create_xml_doc($datas, $path); break;}
            case 'csv' : { $this->create_csv_doc($datas, $path); break;}
            case 'json' : { $this->create_json_doc($datas, $path); break;}
            default: print "Неправильно указано расширение!"; break;
        }
    }

    #Метод создания выбранного пути и проверка на правильность ввода
    function create_dir($path){
        if(!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        if (!is_readable($path)) {
            throw new Exception("Неправильный путь или недостаточно прав!");
        }
    }

    #Метод для создания файла вw формате XML
    function create_xml_doc(array $datas, string $path){
        $dom = new domDocument("1.0", "utf-8"); #Создаём XML-документ версии 1.0 с кодировкой utf-8
        $dom->formatOutput=true;
        $urlset = $dom->createElement("urlset"); #Создаём корневой элемент
        $urlset->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $urlset->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
        $urlset->setAttribute("xsi:schemaLocation", "http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd");
        $dom->appendChild($urlset);
        for ($i = 0; $i < count($datas[0]); $i++) {
            $url = $dom->createElement("url"); #Создаём узел "url"
            $loc = $dom->createElement("login", $datas[0][$i]); #Создаём узел "login" с текстом внутри
            $lastmode = $dom->createElement("password", $datas[1][$i]); #Создаём узел "password" с текстом внутри
            $priority = $dom->createElement("priority", $datas[2][$i]); #Создаём узел "priority" с текстом внутри
            $changefreq = $dom->createElement("changefreq", $datas[3][$i]); #Создаём узел "changefreq" с текстом внутри
            $url->appendChild($loc); #Добавляем в узел "url" узел "login"
            $url->appendChild($lastmode); #Добавляем в узел "url" узел "password"
            $url->appendChild($priority); #Добавляем в узел "url" узел "priority"
            $url->appendChild($changefreq);#Добавляем в узел "url" узел "changefreq"
            $urlset->appendChild($url); #Добавляем в корневой узел "urlset" узел "url"
        }
        $dom->save($path."/sitemap.xml"); #Сохраняем полученный XML-документ в файл
    }

    #Метод для создания файла в формате CSV
    function create_csv_doc(array $datas, string $path){
    $CSV_string = null; #Пустая строка для преобразования в CSV
    $CSV_string = "loc, lastmodes, prioritys, changefreqs"; #Запись заголовка в строку
    #$CSV_string = iconv( "UTF-8", "cp1251",  $CSV_string ); #Изменение кодировки строки из UTF-8 в cp1251
    for ($i = 0; $i < count($datas[0]); $i++) {
        $CSV_string = iconv("cp1251", "UTF-8", $CSV_string ); #Изменение кодировки строки из cp1251 в UTF-8 для правильного отображения
        $CSV_string = $CSV_string."\n".$datas[0][$i].",".$datas[1][$i].",".$datas[2][$i].",".$datas[3][$i]; #
        $CSV_string = iconv( "UTF-8", "cp1251",  $CSV_string ); #Изменение кодировки строки из UTF-8 в cp1251
    }
    file_put_contents($path."sitemap.csv", $CSV_string ); #Создаем csv файл и записываем в него строку
    }

    #Метод для создания файла в формате JSON
    function create_json_doc(array $datas, string $path){
    $file = fopen($path."sitemap.json", "w"); #Создаем новый файл
    for ($i = 0; $i < count($datas[0]); $i++) {
        $info = 
        [
            "loc" => $datas[0][$i],
            "lastmode" => $datas[1][$i],
            "priority" => $datas[2][$i],
            "changefreq" => $datas[3][$i],
        ];
        // преобразовываем его в json вид
        $json = json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        // и записываем туда данные
        fwrite($file,$json);
    }
    //закрываем файл
    fclose($file);
    }
}
?>
