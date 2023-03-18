<?php

if (!class_exists('\\DLTemplate')) {
    include_once(MODX_BASE_PATH . 'assets/snippets/DocLister/lib/DLTemplate.class.php');
}
$DLT = DLTemplate::getInstance($modx);

$compareMaxCountMsg = [
    'en' => 'You can only compare up to ' . $compareMaxCount . ' products at a time. Please remove a product from the comparison list to add a new one.',
    'ru' => 'Вы можете сравнивать не более ' . $compareMaxCount . ' товаров одновременно. Пожалуйста, удалите товар из сравнения и добавьте другой.',
];

if ($modx->event->name === 'OnPageNotFound') {
    // Render Compare table with products
    if (isset($_GET['q']) && $_GET['q'] == 'compare-list-render') {
        $html = '';
        if (!empty($_COOKIE['compare_list'])) {
            /**
             * 1. Формируем массив из JSON'а с параметрами документов
             */

            $requiredParams = [
                'idType' => 'documents',
                'documents' => $_COOKIE['compare_list'],
                'display' => $compareMaxCount,
            ];

            // Выбираем из списков rowsList и requiredList имена с подстрокой 'tv.'
            // и формируем из таких имен список tvList (сначала удаляя префиксы) для вызова DocLister
            $tvPrefix = "tv.";
            $arr['tvList'] = array();
            $params['tvList'] = '';

            foreach (['rowsList', 'requiredList'] as $listName) {
                $params[$listName] = str_replace(' ', '', $params[$listName]);

                if (!empty($params[$listName])) {
                    $arr[$listName] = explode(',', $params[$listName]);

                    foreach ($arr[$listName] as $paramName) {
                        if (strpos($paramName, $tvPrefix) !== false) {
                            $arr['tvList'][] = $paramName;
                        }
                    }
                }
                $params['tvList'] = str_replace($tvPrefix, '', implode(',', $arr['tvList']));
            }

            $result = $modx->runSnippet('DocLister', array_merge($requiredParams, $params));
            $result = json_decode($result, true);
            //echo $modx->parseDocumentSource(var_dump($result));


            /**
             * 2. Шаблонизируем сформированный массив с параметрами документов
             */

            // Верхняя строка таблицы
            $ph['wrap'] = '';
            foreach ($result as $document) {
                $ph['wrap'] .= $DLT->parseChunk($params['topRowItemTpl'], $document);
            }
            $ph['topRow'] = $DLT->parseChunk($params['topRowTpl'], $ph);

            //Строки в теле таблицы
            $ph['wrap'] = $ph["row"] = '';
            if (is_array($arr['rowsList']) && !empty($arr['rowsList'])) {
                $arr['rowsNames'] = explode('|', $params['rowsNames']); // Имена для строк
                $arr['rowsList'] = str_replace('.', '_', $arr['rowsList']); // В режиме АПИ у ТВ не точки, а подчеркивания
                for ($i = 0; $i < count($arr['rowsList']); $i++) {
                    foreach ($result as $document) {
                        $ph['value'] = $document[$arr['rowsList'][$i]];
                        $ph['row'] .= $DLT->parseChunk($params['paramTpl'], $ph);
                    }
                    $ph['class'] = ($i % 2) ? 'odd' : 'even';
                    $ph['rowName'] =  $arr['rowsNames'][$i];
                    $ph["wrap"] .= $DLT->parseChunk($params['rowTpl'], $ph);
                    $ph['row'] = '';
                }
            }
            $ph['rows'] = $DLT->parseChunk($params['rowsOwner'], $ph);

            // Если всего 1 товар, то добавляем ещё напоминание, что 1 товар - это мало
            if (count(explode(',', $_COOKIE['compare_list'])) == 1) {               
                $ph['compareOneMsg'] = $DLT->parseChunk($params['compareOneTpl'], $ph);
            } else {
                $ph['compareOneMsg'] = '';
            }
            $html = $DLT->parseChunk($params['ownerTPL'], $ph);
        } else {
            $html = $DLT->parseChunk($params['compareEmptyTpl'], $ph);
        }
        echo $modx->parseDocumentSource($html);
        die();
    }
} elseif ($modx->event->name === 'OnWebPageInit') {
    // Load Scripts and JS constants values
    $jsConst = "const compareMaxCount = {$compareMaxCount};" . PHP_EOL;
    $jsConst .= 'const compareMaxCountMsg = "' . $compareMaxCountMsg['ru'] . '";' . PHP_EOL;
    $jsConst .= 'const compareActiveClass = "' . $compareActiveClass . '";' . PHP_EOL;


    $modx->regClientScript('<script>' . $jsConst . '</script>');
    $modx->regClientScript('assets/snippets/Compare/compare.js');
}
