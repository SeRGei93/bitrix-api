var url = "",
    fullUrl,
    id = "",
    text = "",
    string = "",
    alert = "",
    idNumber = 0,
    idElement = "",
    duration = 0;

// Плавный скролл до элемента
function scrollTo(idElement, duration = 500) {
    $('html, body').animate({ scrollTop: $(idElement).offset().top}, duration);
}

// Вывод информационного сообщения
function showMessage(text = "", id = "#main", alert = "info") {
    idNumber++;
    string = "<div id='alert" + idNumber + "' class='alert alert-" + alert + "' role='alert'>" + text + "</div>";
    $(id).append(string);
    console.log(text);
}

// Функция для работы с Ajax
function ajaxCommunication(iblock, step, count, apikey, current, property, element_id, iblock_ids, process) {
    $.ajax({
        method: "GET", // метод HTTP, используемый для запроса
        url: url, // строка, содержащая URL адрес, на который отправляется запрос
        data: { // данные, которые будут отправлены на сервер
            IBLOCK: iblock,
            STEP: step,
            COUNT: count,
            apikey: apikey,
            current: current,
            PROPERTY: property,
            element_id: element_id,
            iblock_ids: iblock_ids,
            process: process
        },
        error: function (xhr) {
            showMessage("Ошибка работы Ajax! " + xhr.status + " " + xhr.statusText, "#main", "danger");
        },
        success: function (data) {
            // console.log(data);
            data = $.parseJSON(data);
            console.log(data);
            if (data.status = "success") {
                if (data.current) {
                    showMessage(data.message, "#main", "success");
                }
                current = data.current + 1;
                if (current <= count) {
                    // Рекурсия
                    ajaxCommunication(iblock, step, count, apikey, current, property, element_id, iblock_ids, process);
                } else {
                    showMessage("Операции c ИБ №" + iblock + " закончены!", "#main", "info");
                    scrollTo("#alert" + idNumber, 500);
                }
            } else {
                showMessage("Ошибка: " + data, "#main", "danger");
            }
        }
    });
}

// Получение GET параметров из URL
function getUrlParam(name) {
    if (!fullUrl) {
        fullUrl = window.location.href;
    }
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(fullUrl);
    if (results == null) {
        return null;
    }
    else {
        return decodeURI(results[1]) || 0;
    }
}

// Получение полного URL
function getUrl() {
    return window.location.origin + window.location.pathname;
}

// Запуск сразу после загрузки страницы
$(document).ready(function () {
    var iblock = Number(getUrlParam('IBLOCK')),
        step = Number(getUrlParam('STEP')),
        count = Number(getUrlParam('COUNT')),
        apikey = String(getUrlParam('apikey')),
        current = 1,
        property = String(getUrlParam('PROPERTY')),
        element_id = Number(getUrlParam('element_id')),
        iblock_ids = getUrlParam('iblock_ids'),
        process = String(getUrlParam('process')),
        arrayIblocks = [],
        i = 0;

    url = getUrl();

    if ((url.indexOf("api_delete.php") != -1) && (iblock_ids != null)) {
        arrayIblocks = iblock_ids.split(',');
        process = "DELETE";
        for (i = 0; i < arrayIblocks.length; i++) {
            if (arrayCount[i] == 0) {
                showMessage("Информационный блок №" + arrayIblocks[i] + " пуст!", "#main", "danger");
            } else {
                count = (element_id == 0) ? arrayCount[i] : 1;
                showMessage("Элементов для удаления в ИБ №" + arrayIblocks[i] + ": " + count, "#main", "info");
                ajaxCommunication(arrayIblocks[i], step, count, apikey, current, property, element_id, iblock_ids, process);
            }
            current = 1;
        }
    } else {
        process = "ADD";
        ajaxCommunication(iblock, step, count, apikey, current, property, element_id, iblock_ids, process);
    }
});