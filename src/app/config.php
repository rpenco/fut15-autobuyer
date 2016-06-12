<?php

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/conf/config.php';
require_once __DIR__.'/fonc/functions.php';

use Fut\Log;

function checked($INI_FILE, $ini_key)
{
    if ($INI_FILE[$ini_key]) {
        return ' checked="checked" ';
    }
}

if (isset($_POST) && !empty($_POST)) {
    $INI_FILE['enable'] = (isset($_POST['checkboxG1'])) ? 1 : 0;
    $INI_FILE['allow_quick'] = (isset($_POST['checkboxG2'])) ? 1 : 0;
    $INI_FILE['allow_trade'] = (isset($_POST['checkboxG3'])) ? 1 : 0;
    $INI_FILE['allow_sold'] = (isset($_POST['checkboxG4'])) ? 1 : 0;
    $INI_FILE['allow_quick_buy'] = (isset($_POST['checkboxG5'])) ? 1 : 0;
    $INI_FILE['allow_trade_buy'] = (isset($_POST['checkboxG6'])) ? 1 : 0;

    write_php_ini($INI_FILE, __DIR__.'/conf/config.ini');
    Log::I('Configuration mise à jour: enable: '.$INI_FILE['enable'].', allow_quick: '.$INI_FILE['allow_quick'].', allow_trade: '.$INI_FILE['allow_trade'].', allow_sold: '.$INI_FILE['allow_sold'].', allow_quick_buy: '.$INI_FILE['allow_quick_buy'].', allow_trade_buy: '.$INI_FILE['allow_trade_buy'].'.');
}

?>


<form method="POST" action="#">

	<div style="background: #444; color: #fafafa; padding: 10px;">
		<table>
		<tr><th colspan="3" class="title">Configuration générale</th></tr>
			<tr>
				<th>Service "Autobuyer"</th>
				<th>Service "Quick Sell"</th>
				<th>Service "Trade"</th>
				<th>Autoriser à vendre</th>
			</tr>
			<tr>
				<td><input type="checkbox" name="checkboxG1" id="checkboxG1" class="css-checkbox" onchange="submit()" <?php echo checked($INI_FILE, 'enable'); ?>/><label for="checkboxG1" class="css-label"></label></td>
				<td><input type="checkbox" name="checkboxG2" id="checkboxG2" class="css-checkbox" onchange="submit()" <?php echo checked($INI_FILE, 'allow_quick'); ?> /><label for="checkboxG2" class="css-label"></label></td>
				<td><input type="checkbox" name="checkboxG3" id="checkboxG3" class="css-checkbox" onchange="submit()" <?php echo checked($INI_FILE, 'allow_trade'); ?>/><label for="checkboxG3" class="css-label"></label></td>
				<td><input type="checkbox" name="checkboxG4" id="checkboxG4" class="css-checkbox" onchange="submit()" <?php echo checked($INI_FILE, 'allow_sold'); ?>/><label for="checkboxG4" class="css-label"></label></td>
			</tr>

		<tr><th colspan="3" class="title">Mode Quick Sell</th></tr>

			<tr>
				<th>Authoriser achat</th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
			<tr>
				<td><input type="checkbox" name="checkboxG5" id="checkboxG5" class="css-checkbox" onchange="submit()" <?php echo checked($INI_FILE, 'allow_quick_buy'); ?>/><label for="checkboxG5" class="css-label"></label></td>
				<td></td>
			</tr>
		<tr><th colspan="3" class="title">Mode Trade</th></tr>
			<tr>
				<th>Authoriser achat</th>
				<th></th>
				<th></th>
			</tr>
			<tr>
				<td><input type="checkbox" name="checkboxG6" id="checkboxG6" class="css-checkbox" onchange="submit()" <?php echo checked($INI_FILE, 'allow_trade_buy'); ?>/><label for="checkboxG6" class="css-label"></label></td>
				<td></td>
				<td></td>
			     <td></td>
			</tr>
		</table>
	</div>
</form>
