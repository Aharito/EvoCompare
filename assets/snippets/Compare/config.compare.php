<?php

// Здесь задайте макс. кол-во товаров в сравнении
$compareMaxCount = 5;

// Класс активности
$compareActiveClass = 'active';

// Здесь задайте остальные параметры по правилам ДокЛистера. Применимы все параметры ДокЛистера.
// (!) Список ниже - это пример, вы можете одни параметры удалить, вторые поменять, а третьи добавить
// (!!) Не действует параметр tvList, так как он автоматом формируется из rowsList и requiredList
$params = [
    'ownerTPL' => '@CODE:
    <div class="row page-block__grid mb-4">
        <div class="table">
            <table>
                [+wrap+]
            </table>
        </div>
    </div>',
    'topRowTpl' => '@CODE:<tr class="first"><td><b>Параметры<b></td>[+wrap+]</tr>',        
    // Это пример с инлайн-шаблоном
    'topRowItemTpl' => '@CODE:
    <td>
        <a href="[+url+]"><img class="img-fluid" style="width: 200px" src="[+picture+]"></a><br>
        <span class="btn btn-sm btn-info my-3" data-role="compareButton" data-action="addToCompareList" data-id="[+id+]">
            <i class="fas fa-chart-bar"></i> 
        </span>
    </td>',
    // А это пример с чанком
    //'topRowItemTpl' => '@CHUNK:topRowItemTpl',
    'rowTpl' => '@CODE:<tr class="[+class+]"><td><small><b>[+rowName+]</b></small></td>[+wrap+]</tr>',
    'paramTpl' => '@CODE:<td>[+value+]</td>',
    // Список полей документа, ТВ-параметров, препаре-"полей".
    // В том порядке, в котором надо отображать, через запятую. ТВ-параметры пишем как tv.dlina
    'rowsList' => 'tv.nazv,tv.vid,tv.tol,tv.dlina,tv.shir,tv.color,tv.plotn,tv.razm,tv.price', 
    // Названия для параметров из rowsList, в том же порядке, что и в rowsList, разделение верт. чертой "|"
    'rowsNames' => 'Название|Вид|Толщина|Длина|Ширина,<br>мм|Цвет|Плотность|Размер|Цена',
    // Доп. список величин, которые не нужны в сравнении, но нужны в шаблонах или для prepare, или для выборки
    // Порядок не важен, ТВ-параметры также пишем как префикс с именем tv.dlina
    'requiredList' => 'picture',
    'sortBy' => 'menuindex',
    'sortOrder' => 'ASC',
    'prepare' => 'Tovar_picture,Tovar_labels,Tovar_data',
    'api' => '1',
    'debug' => 0,
];
