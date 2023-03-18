<?php

// Здесь задайте макс. кол-во товаров в сравнении
$compareMaxCount = 5;

// Класс активности
$compareActiveClass = 'active';

// Здесь задайте остальные параметры по правилам ДокЛистера. Применимы все параметры ДокЛистера.
// (!) Список ниже - это пример, вы можете одни параметры удалить, вторые поменять, а третьи добавить
// (!!) Не действует параметр tvList, так как он автоматом формируется из rowsList и requiredList
// (!!) Не действует параметр display, так как он равен кол-ву товаров в сравнении
$params = [
    'ownerTPL' => '@CODE:
    <div class="my-5">
        <div class="btn btn-danger btn-md" data-role="compareClearButton">Очистить сравнение</div>
    </div>
    [+compareOneMsg+]
    <div class="row page-block__grid mb-4">
        <div class="table">
            <table>
                [+topRow+]                
                [+rows+]
            </table>
        </div>        
    </div>',
    'topRowTpl' => '@CODE:<thead><tr class="first"><td><b>Параметры<b></td>[+wrap+]</tr></thead>',
    'topRowItemTpl' => '@CODE:
    <td>
        <a href="[+url+]"><img class="img-fluid" style="width: 200px" src="[+picture+]"></a><br>
        <span class="btn btn-sm btn-info my-3" data-role="compareButton" data-action="addToCompareList" data-id="[+id+]">
            <i class="fas fa-chart-bar"></i>
        </span>
    </td>',
    'rowsOwner' => '@CODE:<tbody>[+wrap+]</tbody>',
    'rowTpl' => '@CODE:<tr class="[+class+]"><td><small><b>[+rowName+]</b></small></td>[+row+]</tr>',
    'paramTpl' => '@CODE:<td>[+value+]</td>',
    'compareOneTpl' => '@CODE:
    <div class="alert alert-info text-center">
        <div class="mt-3 mb-4"><h3>Всего один товар</h3></div>
        <div class="d-inline-block mb-4 w-50">
            <div class="alert alert-lg">Добавьте ещё. Одного товара недостаточно для сравнения.</div>
        </div>
        <div class="mb-3"><a class="btn btn-info btn-md" href="sotovyj-polikarbonat/">Выбрать товары</a></div>
    </div>',
    'compareEmptyTpl' => '@CODE:
    <div class="alert alert-info text-center">
        <div class="mt-3 mb-4"><h3>Нет товаров для сравнения</h3></div>
        <div class="d-inline-block mb-4 w-50">
            <div class="alert alert-lg">Вы не выбрали ни одного товара. Нужно добавить товары для сравнения.</div>
        </div>
        <div class="mb-3"><a class="btn btn-danger btn-md" href="sotovyj-polikarbonat/">Выбрать товары</a></div>
    </div>',
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
