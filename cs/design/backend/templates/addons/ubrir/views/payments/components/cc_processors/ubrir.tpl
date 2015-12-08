{assign var="uni_url" value="payment_notification.uni_resp&payment=ubrir"|fn_url:'C':'http'}
{assign var="admin_url" value="payment_notification.admin_button"|fn_url:'C':'http'}
<p>
    {__("addons.ubrir.uni_url", [ "[uni_url]" => $uni_url ])}
</p>
<hr>
<div class="control-group">
    <label class="control-label" for="two">{__("addons.ubrir.two")}:</label>
    <div class="controls">
        <input type="checkbox" name="payment_data[processor_params][two]" id="two" value="Y" {if $processor_params.two == 'Y'} checked="checked"{/if}/>
    </div>
</div>
<h2>Настройки VISA</h2>
<div class="control-group">
    <label class="control-label" for="twpg_id">{__("addons.ubrir.twpg_id")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][twpg_id]" id="twpg_id" value="{$processor_params.twpg_id}" size="60">
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="twpg_pass">{__("addons.ubrir.twpg_pass")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][twpg_pass]" id="twpg_pass" value="{$processor_params.twpg_pass}" size="60">
    </div>
</div>
<h2>Настройки MasterCard</h2>
<div class="control-group">
    <label class="control-label" for="uni_id">{__("addons.ubrir.uni_id")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][uni_id]" id="uni_id" value="{$processor_params.uni_id}" size="60">
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="uni_login">{__("addons.ubrir.uni_login")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][uni_login]" id="uni_login" value="{$processor_params.uni_login}" size="60">
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="uni_pass">{__("addons.ubrir.uni_pass")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][uni_pass]" id="uni_pass" value="{$processor_params.uni_pass}" size="60">
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="uni_userpass">{__("addons.ubrir.uni_userpass")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][uni_userpass]" id="uni_userpass" value="{$processor_params.uni_userpass}" size="60">
    </div>
</div>

             

<div style="margin: 20px 0 20px 0; text-align: center; padding: 20px; width: 415px; border: 1px dashed #999;"> 
<h3 style="text-align: center; padding: 0 0 20px 0; margin: 0;">Получить детальную информацию:</h3>
<div style="margin: 0 auto; text-align: center; padding: 5px; width: 200px; border: 1px dashed #999;">Номер заказа: <br>
<input style="margin: 5px; width: 150px;" type="text" name="shoporderidforstatus" id="shoporderidforstatus" value="" placeholder="№ заказа" size="8">
<input style="margin: 5px;" type="hidden" name="task_ubrir" id="task_ubrir" value="">
  <input class="twpginput" type="button"  id="statusbutton" value="Запросить статус заказа" onclick="fn_get_status(this)">
  <input class="twpginput" type="button"  id="detailstatusbutton" value="Информация о заказе" onclick="fn_get_detailstatus()">
  <input class="twpginput" type="button"  id="reversbutton" value="Отмена заказа" onclick="fn_get_reverse()"><br>
</div>  
  <input class="twpgbutton" type="button" id="recresultbutton" value="Сверка итогов" onclick="fn_get_reconcile()">
  <input class="twpgbutton" type="button" id="journalbutton" value="Журнал операций Visa" onclick="fn_get_journal()">
  <input class="twpgbutton" type="button" id="unijournalbutton" value="Журнал операций MasterCard" onclick="fn_get_uni_journal()">
</div>
<table>
<tr>
    <div id="callback" class="hide">
      <table>
        <tr>
          <h2>Обратная связь</h2>
        </tr>
        <tr>
         <td>Тема</td>
            <td>
            <select name="subject" id="mailsubject" style="width:150px">
              <option selected>Выберите тему</option>
              <option value="Подключение услуги">Подключение услуги</option>
              <option value="Продление Сертификата">Продление Сертификата</option>
              <option value="Технические вопросы">Технические вопросы</option>
              <option value="Юридические вопросы">Юридические вопросы</option>
              <option value="Бухгалтерия">Бухгалтерия</option>
              <option value="Другое">Другое</option>
            </select>
            </td>
          </tr>
          <tr>
              <td>Телефон</td>
              <td>
                <input type="text" name="email" id="mailem" style="width:150px">
              </td>
          </tr>
        <tr>
        <td>Сообщение</td>
          <td>
            <textarea name="maildesc" id="maildesc" cols="30" rows="10" style="width:150px;resize:none;"></textarea>
          </td>
        </tr>
        <tr><td></td>
          <td id="mailresponse"><input id="sendmail" type="button" name="sendmail" value="Отправить" onclick="fn_send_mail()">
        </tr>
      </table>
    </div>
</tr>
<tr>
			</tr>
			<tr><td></td><td></td><td>8 (800) 1000-200</td></tr>
</table>
<div style="width: 100%; margin-top: 10px;" id="ajaxresponse"></div>
<script>

    // Обработчик кнопки статус заказа
    function fn_send_mail() {
         var mailsubject = $('#mailsubject').val();
         var maildesc = $('#maildesc').val();
         var mailem = $('#mailem').val();
        var url = '{$admin_url}';
        var formData = new FormData();
        formData.append("action","send_mail");
        formData.append("mailsubject", mailsubject);
        formData.append("maildesc", maildesc);
        formData.append("mailem", mailem);
        var ajax = new XMLHttpRequest();
        ajax.open("POST", url+"&payment=ubrir");
        ajax.send(formData);
        ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            console.log(ajax.responseText);
            $('#ajaxresponse').html(ajax.responseText);
            if (ajax.responseText =="Письмо отправлено") {
            $('#maildesc').val(null);
            $('#mailsubject').val(null);
            $('#mailem').val(null);
            };
        }
      }
    }
    // Обработчик кнопки статус заказа
    function fn_get_status() {
        var order_id = $('#shoporderidforstatus').val();
        var url = '{$admin_url}';
        var formData = new FormData();
        formData.append("action","orderstatus");
        formData.append("order_id",order_id);
        var ajax = new XMLHttpRequest();
        ajax.open("POST", url+"&payment=ubrir");
        ajax.send(formData);
        ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            $('#ajaxresponse').html(ajax.responseText);
        }
      }
    }
    // Обработчик кнопки детальный статус заказа
    function fn_get_detailstatus() {
        var order_id = $('#shoporderidforstatus').val();
        var url = '{$admin_url}';
        var formData = new FormData();
        formData.append("action","orderdetailstatus");
        formData.append("order_id",order_id);
        var ajax = new XMLHttpRequest();
        ajax.open("POST", url+"&payment=ubrir");
        ajax.send(formData);
        ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            $('#ajaxresponse').html(ajax.responseText);
        }
      }
    }
    // Обработчик кнопки вернуть деньги
    function fn_get_reverse() {
        var order_id = $('#shoporderidforstatus').val();
        var url = '{$admin_url}';
        var formData = new FormData();
        formData.append("action","reverse");
        formData.append("order_id",order_id);
        var ajax = new XMLHttpRequest();
        ajax.open("POST", url+"&payment=ubrir");
        ajax.send(formData);
        ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            $('#ajaxresponse').html(ajax.responseText);
        }
      }
    }
    // Обработчик кнопки сверка итогов
    function fn_get_reconcile() {
        var url = '{$admin_url}';
        var formData = new FormData();
        formData.append("action","reconcile");
        formData.append("twpg_id",'{$processor_params.twpg_id}');
        formData.append("twpg_pass",'{$processor_params.twpg_pass}');
        var ajax = new XMLHttpRequest();
        ajax.open("POST", url+"&payment=ubrir");
        ajax.send(formData);
        ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            $('#ajaxresponse').html(ajax.responseText);
        }
      }
    }
    // Обработчик кнопки сверка итогов
    function fn_get_journal() {
        var url = '{$admin_url}';
        var formData = new FormData();
        formData.append("action","journal");
        formData.append("twpg_id",'{$processor_params.twpg_id}');
        formData.append("twpg_pass",'{$processor_params.twpg_pass}');
        var ajax = new XMLHttpRequest();
        ajax.open("POST", url+"&payment=ubrir");
        ajax.send(formData);
        ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            $('#ajaxresponse').html(ajax.responseText);
        }
      }
    }
    // Обработчик кнопки сверка итогов
    function fn_get_uni_journal() {
        var url = '{$admin_url}';
        var formData = new FormData();
        formData.append("action","uni_journal");
        formData.append("uni_login",'{$processor_params.uni_login}');
        formData.append("uni_pass",'{$processor_params.uni_userpass}');
        var ajax = new XMLHttpRequest();
        ajax.open("POST", url+"&payment=ubrir");
        ajax.send(formData);
        ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            $('#ajaxresponse').html(ajax.responseText);
        }
      }
    }
</script>